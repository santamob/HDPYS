using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using NToastNotify;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.DeleteGoal;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.SubmitForApproval;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetActivePeriods;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetAvailableIndicators;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetGoalById;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetMyGoals;
using PYS.Web.ViewModels.MyGoals;

namespace PYS.Web.Controllers
{
    /// <summary>
    /// Çalışan Hedef Girişi Controller'ı.
    /// Çalışanların kendi performans hedeflerini oluşturup yönetici onayına gönderdiği ekranları yönetir.
    /// </summary>
    [Authorize]
    [Route("hedeflerim")]
    public class MyGoalsController(
        ILogger<MyGoalsController> logger,
        IMapper mapper,
        IMediator mediator,
        IToastNotification toastNotification
    ) : Controller
    {
        /// <summary>
        /// Hedeflerim ana sayfa - Dashboard ve hedef listesi
        /// </summary>
        [HttpGet("")]
        public async Task<IActionResult> Index(Guid? periodId, int? status)
        {
            var periods = await mediator.Send(new GetActivePeriodsRequest());
            var goalsResponse = await mediator.Send(new GetMyGoalsQueryRequest
            {
                PeriodId = periodId,
                Status = status
            });

            var viewModel = new MyGoalsIndexViewModel
            {
                Goals = goalsResponse.Goals,
                Periods = periods,
                SelectedPeriodId = periodId,
                SelectedStatus = status,
                TotalCount = goalsResponse.TotalCount,
                DraftCount = goalsResponse.DraftCount,
                PendingFirstApprovalCount = goalsResponse.PendingFirstApprovalCount,
                PendingSecondApprovalCount = goalsResponse.PendingSecondApprovalCount,
                ApprovedCount = goalsResponse.ApprovedCount,
                RejectedCount = goalsResponse.RejectedCount,
                TotalWeight = goalsResponse.TotalWeight
            };

            return View(viewModel);
        }

        /// <summary>
        /// Yeni hedef oluşturma sayfası - Dönem seçimi ve gösterge havuzu
        /// </summary>
        [HttpGet("yeni")]
        public async Task<IActionResult> Create(Guid? periodId)
        {
            var periods = await mediator.Send(new GetActivePeriodsRequest());
            var indicators = await mediator.Send(new GetAvailableIndicatorsRequest());

            var selectedPeriodId = periodId ?? (periods.Any() ? periods.First().Id : Guid.Empty);

            // Mevcut dönemdeki hedeflerin toplam ağırlığını hesapla
            decimal currentTotalWeight = 0;
            if (selectedPeriodId != Guid.Empty)
            {
                var goalsResponse = await mediator.Send(new GetMyGoalsQueryRequest { PeriodId = selectedPeriodId });
                currentTotalWeight = goalsResponse.TotalWeight;
            }

            var viewModel = new CreateGoalViewModel
            {
                PeriodId = selectedPeriodId,
                Periods = periods,
                Indicators = indicators,
                CurrentTotalWeight = currentTotalWeight
            };

            return View(viewModel);
        }

        /// <summary>
        /// Yeni hedef oluşturma POST işlemi
        /// </summary>
        [HttpPost("yeni")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Create(CreateGoalViewModel model)
        {
            var request = new CreateGoalCommandRequest
            {
                PeriodId = model.PeriodId,
                Goals = model.Goals.Select(g => new CreateGoalItem
                {
                    IndicatorId = g.IndicatorId,
                    GoalTitle = g.GoalTitle,
                    GoalDescription = g.GoalDescription,
                    TargetValue = g.TargetValue,
                    TargetUnit = g.TargetUnit,
                    Weight = g.Weight,
                    StartDate = g.StartDate,
                    EndDate = g.EndDate,
                    EmployeeNote = g.EmployeeNote
                }).ToList()
            };

            var result = await mediator.Send(request);

            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage("Hedefler başarıyla kaydedildi.", new ToastrOptions { Title = "Başarılı" });
                return RedirectToAction(nameof(Index));
            }

            foreach (var error in result.Errors)
                toastNotification.AddErrorToastMessage(error, new ToastrOptions { Title = "Hata" });

            // Form verilerini yeniden doldur
            var periods = await mediator.Send(new GetActivePeriodsRequest());
            var indicators = await mediator.Send(new GetAvailableIndicatorsRequest());
            model.Periods = periods;
            model.Indicators = indicators;
            return View(model);
        }

        /// <summary>
        /// Hedef düzenleme sayfası
        /// </summary>
        [HttpGet("duzenle/{id}")]
        public async Task<IActionResult> Edit(Guid id)
        {
            var goal = await mediator.Send(new GetGoalByIdRequest { Id = id });
            if (goal == null)
            {
                toastNotification.AddErrorToastMessage("Hedef bulunamadı.", new ToastrOptions { Title = "Hata" });
                return RedirectToAction(nameof(Index));
            }

            // Mevcut toplam ağırlığı hesapla (bu hedef hariç)
            var goalsResponse = await mediator.Send(new GetMyGoalsQueryRequest { PeriodId = goal.PeriodId });
            var totalWeightExcludingCurrent = goalsResponse.TotalWeight - goal.Weight;

            var viewModel = new EditGoalViewModel
            {
                Id = goal.Id,
                PeriodId = goal.PeriodId,
                PeriodName = goal.PeriodName,
                IndicatorId = goal.IndicatorId,
                IndicatorName = goal.IndicatorName,
                GoalTitle = goal.GoalTitle,
                GoalDescription = goal.GoalDescription,
                TargetValue = goal.TargetValue,
                TargetUnit = goal.TargetUnit,
                Weight = goal.Weight,
                StartDate = goal.StartDate,
                EndDate = goal.EndDate,
                EmployeeNote = goal.EmployeeNote,
                Status = goal.Status,
                CurrentTotalWeight = totalWeightExcludingCurrent
            };

            return View(viewModel);
        }

        /// <summary>
        /// Hedef düzenleme POST işlemi
        /// </summary>
        [HttpPost("duzenle/{id}")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Edit(Guid id, EditGoalViewModel model)
        {
            var request = new UpdateGoalCommandRequest
            {
                Id = id,
                GoalTitle = model.GoalTitle,
                GoalDescription = model.GoalDescription,
                TargetValue = model.TargetValue,
                TargetUnit = model.TargetUnit,
                Weight = model.Weight,
                StartDate = model.StartDate,
                EndDate = model.EndDate,
                EmployeeNote = model.EmployeeNote
            };

            var result = await mediator.Send(request);

            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage("Hedef başarıyla güncellendi.", new ToastrOptions { Title = "Başarılı" });
                return RedirectToAction(nameof(Index));
            }

            foreach (var error in result.Errors)
                toastNotification.AddErrorToastMessage(error, new ToastrOptions { Title = "Hata" });

            return View(model);
        }

        /// <summary>
        /// Hedef silme (AJAX) - Soft delete
        /// </summary>
        [HttpPost("sil/{id}")]
        public async Task<IActionResult> Delete(Guid id)
        {
            var result = await mediator.Send(new DeleteGoalCommandRequest { Id = id });

            if (result.Success)
            {
                return Json(new { success = true, message = "Hedef başarıyla silindi." });
            }

            return Json(new { success = false, message = result.Errors.FirstOrDefault() ?? "Silme işlemi başarısız." });
        }

        /// <summary>
        /// Önizleme sayfası - Seçilen dönemdeki tüm hedeflerin özeti
        /// </summary>
        [HttpGet("onizleme")]
        public async Task<IActionResult> Preview(Guid periodId)
        {
            var goalsResponse = await mediator.Send(new GetMyGoalsQueryRequest { PeriodId = periodId });
            var periods = await mediator.Send(new GetActivePeriodsRequest());
            var period = periods.FirstOrDefault(p => p.Id == periodId);

            var viewModel = new PreviewGoalsViewModel
            {
                PeriodId = periodId,
                PeriodName = period?.DisplayName ?? "",
                Goals = goalsResponse.Goals,
                TotalWeight = goalsResponse.TotalWeight
            };

            return View(viewModel);
        }

        /// <summary>
        /// Yönetici onayına gönderme POST işlemi
        /// </summary>
        [HttpPost("onizleme")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> SubmitForApproval(Guid periodId)
        {
            var result = await mediator.Send(new SubmitForApprovalCommandRequest { PeriodId = periodId });

            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(result.Message ?? "Hedefler onaya gönderildi.", new ToastrOptions { Title = "Başarılı" });
                return RedirectToAction(nameof(Index));
            }

            foreach (var error in result.Errors)
                toastNotification.AddErrorToastMessage(error, new ToastrOptions { Title = "Hata" });

            return RedirectToAction(nameof(Preview), new { periodId });
        }

        /// <summary>
        /// Hedef detay sayfası
        /// </summary>
        [HttpGet("detay/{id}")]
        public async Task<IActionResult> Detail(Guid id)
        {
            var goal = await mediator.Send(new GetGoalByIdRequest { Id = id });
            if (goal == null)
            {
                toastNotification.AddErrorToastMessage("Hedef bulunamadı.", new ToastrOptions { Title = "Hata" });
                return RedirectToAction(nameof(Index));
            }

            return View(goal);
        }
    }
}
