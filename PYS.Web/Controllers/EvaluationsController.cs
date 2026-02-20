using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using NToastNotify;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoals;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoalsSecondLevel;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.RejectGoals;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetActivePeriods;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetMyGoals;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSubordinateGoals;
using PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSecondLevelSubordinateGoals;
using PYS.Web.ViewModels.Evaluations;

namespace PYS.Web.Controllers
{
    /// <summary>
    /// Değerlendirme Formları Controller'ı.
    /// 3 sekmeli görünüm: Kendime Ait, Astlarıma Ait, Astımın Astlarına Ait
    /// İki kademeli onay mekanizması (1. Yönetici -> 2. Üst Yönetici)
    /// </summary>
    [Authorize]
    [Route("degerlendirme")]
    public class EvaluationsController(
        ILogger<EvaluationsController> logger,
        IMapper mapper,
        IMediator mediator,
        IToastNotification toastNotification
    ) : Controller
    {
        /// <summary>
        /// Ana sayfa - 3 sekmeli görüntüleme
        /// </summary>
        [HttpGet("")]
        public async Task<IActionResult> Index(Guid? periodId, string? tab)
        {
            var periods = await mediator.Send(new GetActivePeriodsRequest());

            // Tab 1: Kendime ait hedefler
            var myGoalsResponse = await mediator.Send(new GetMyGoalsQueryRequest { PeriodId = periodId });

            // Tab 2: Astlarıma ait hedefler
            var subordinateResponse = await mediator.Send(new GetSubordinateGoalsRequest { PeriodId = periodId });

            // Tab 3: Astımın astlarına ait hedefler
            var secondLevelResponse = await mediator.Send(new GetSecondLevelSubordinateGoalsRequest { PeriodId = periodId });

            var viewModel = new EvaluationsIndexViewModel
            {
                // Tab 1
                MyGoals = myGoalsResponse.Goals,
                Periods = periods,
                SelectedPeriodId = periodId,
                MyDraftCount = myGoalsResponse.DraftCount,
                MyPendingFirstCount = myGoalsResponse.PendingFirstApprovalCount,
                MyPendingSecondCount = myGoalsResponse.PendingSecondApprovalCount,
                MyApprovedCount = myGoalsResponse.ApprovedCount,
                MyRejectedCount = myGoalsResponse.RejectedCount,
                MyTotalWeight = myGoalsResponse.TotalWeight,

                // Tab 2
                SubordinateGoals = subordinateResponse.Subordinates,
                SubordinatePendingCount = subordinateResponse.PendingApprovalCount,

                // Tab 3
                SecondLevelSubordinateGoals = secondLevelResponse.Subordinates,
                SecondLevelPendingCount = secondLevelResponse.PendingApprovalCount,

                ActiveTab = tab ?? "own"
            };

            return View(viewModel);
        }

        /// <summary>
        /// 1. Yönetici onay (AJAX) - Astın PendingFirstApproval hedeflerini onaylar
        /// </summary>
        [HttpPost("onayla")]
        [IgnoreAntiforgeryToken]
        public async Task<IActionResult> Approve([FromBody] ApproveRejectViewModel model)
        {
            if (model == null || model.PeriodInUserId == Guid.Empty || model.PeriodId == Guid.Empty)
                return Json(new { success = false, message = "Geçersiz istek parametreleri." });

            var result = await mediator.Send(new ApproveGoalsCommandRequest
            {
                PeriodInUserId = model.PeriodInUserId,
                PeriodId = model.PeriodId,
                ManagerNote = model.ManagerNote
            });

            return Json(new { success = result.Success, message = result.Success ? result.Message : result.Errors.FirstOrDefault() });
        }

        /// <summary>
        /// 2. Üst yönetici onay (AJAX) - Astın astının PendingSecondApproval hedeflerini onaylar
        /// </summary>
        [HttpPost("ust-onayla")]
        [IgnoreAntiforgeryToken]
        public async Task<IActionResult> ApproveSecondLevel([FromBody] ApproveRejectViewModel model)
        {
            if (model == null || model.PeriodInUserId == Guid.Empty || model.PeriodId == Guid.Empty)
                return Json(new { success = false, message = "Geçersiz istek parametreleri." });

            var result = await mediator.Send(new ApproveGoalsSecondLevelCommandRequest
            {
                PeriodInUserId = model.PeriodInUserId,
                PeriodId = model.PeriodId,
                ManagerNote = model.ManagerNote
            });

            return Json(new { success = result.Success, message = result.Success ? result.Message : result.Errors.FirstOrDefault() });
        }

        /// <summary>
        /// Red işlemi - Her iki yönetici için (AJAX)
        /// ApprovalLevel: 1 = 1. yönetici reddi, 2 = 2. üst yönetici reddi
        /// </summary>
        [HttpPost("reddet")]
        [IgnoreAntiforgeryToken]
        public async Task<IActionResult> Reject([FromBody] ApproveRejectViewModel model)
        {
            if (model == null || model.PeriodInUserId == Guid.Empty || model.PeriodId == Guid.Empty)
                return Json(new { success = false, message = "Geçersiz istek parametreleri." });

            if (model.ApprovalLevel != 1 && model.ApprovalLevel != 2)
                return Json(new { success = false, message = "Geçersiz onay seviyesi." });

            var result = await mediator.Send(new RejectGoalsCommandRequest
            {
                PeriodInUserId = model.PeriodInUserId,
                PeriodId = model.PeriodId,
                ManagerNote = model.ManagerNote,
                ApprovalLevel = model.ApprovalLevel
            });

            return Json(new { success = result.Success, message = result.Success ? result.Message : result.Errors.FirstOrDefault() });
        }
    }
}
