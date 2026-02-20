using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSecondLevelSubordinateGoals
{
    /// <summary>
    /// 2. derece astların hedeflerini getiren handler.
    /// Mevcut kullanıcının doğrudan astlarının astlarının hedeflerini döner.
    /// KRITIK: Sadece PendingSecondApproval, Approved, Rejected durumundaki hedefler gösterilir.
    /// PendingFirstApproval durumundaki hedefler 2. üst yönetici tarafından görülemez.
    /// </summary>
    public class GetSecondLevelSubordinateGoalsHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<GetSecondLevelSubordinateGoalsHandler> logger
    ) : BaseHandler<IAppDbContext, GetSecondLevelSubordinateGoalsHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetSecondLevelSubordinateGoalsRequest, GetSecondLevelSubordinateGoalsResponse>
    {
        public async Task<GetSecondLevelSubordinateGoalsResponse> Handle(GetSecondLevelSubordinateGoalsRequest request, CancellationToken cancellationToken)
        {
            var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
            if (appUser == null)
            {
                return new GetSecondLevelSubordinateGoalsResponse();
            }

            var managerPerNr = appUser.RegistrationNumber;

            // 1. adım: Doğrudan astları bul
            var directSubordinates = (await unitOfWork.GetAppReadRepository<PeriodInUser>()
                .GetAllAsync(p => p.MPernr == managerPerNr && p.IsActive))
                .ToList();

            if (!directSubordinates.Any())
            {
                return new GetSecondLevelSubordinateGoalsResponse();
            }

            // 2. adım: Doğrudan astların PerNr değerlerini topla
            var directSubPerNrs = directSubordinates.Select(p => (int?)p.PerNr).Distinct().ToList();

            // 3. adım: 2. derece astları bul (MPernr IN directSubPerNrs)
            var secondLevelSubordinates = (await unitOfWork.GetAppReadRepository<PeriodInUser>()
                .GetAllAsync(
                    predicate: p => p.MPernr.HasValue && directSubPerNrs.Contains(p.MPernr) && p.IsActive,
                    include: x => x.Include(p => p.Period)))
                .ToList();

            if (request.PeriodId.HasValue)
            {
                secondLevelSubordinates = secondLevelSubordinates
                    .Where(p => p.PeriodId == request.PeriodId.Value).ToList();
            }

            if (!secondLevelSubordinates.Any())
            {
                return new GetSecondLevelSubordinateGoalsResponse();
            }

            var secondLevelPiuIds = secondLevelSubordinates.Select(p => p.Id).ToList();

            // 4. adım: KRITIK - Sadece PendingSecondApproval, Approved, Rejected hedeflerini çek
            var visibleStatuses = new[] { GoalStatus.PendingSecondApproval, GoalStatus.Approved, GoalStatus.Rejected };
            var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                .GetAllAsync(
                    predicate: g => secondLevelPiuIds.Contains(g.PeriodInUserId)
                                    && g.IsActive
                                    && visibleStatuses.Contains(g.Status),
                    include: x => x.Include(g => g.Indicator!)))
                .ToList();

            // Çalışan bazında grupla
            var subordinateGoals = secondLevelSubordinates.Select(piu =>
            {
                var employeeGoals = goals.Where(g => g.PeriodInUserId == piu.Id).ToList();

                var pendingSecondGoals = employeeGoals
                    .Where(g => g.Status == GoalStatus.PendingSecondApproval).ToList();

                // CanApprove: Tüm hedefler PendingSecondApproval durumunda olmalı
                bool canApprove = pendingSecondGoals.Any()
                    && employeeGoals.All(g => g.Status == GoalStatus.PendingSecondApproval);

                return new SubordinateGoalDto
                {
                    PeriodInUserId = piu.Id,
                    PeriodId = piu.PeriodId,
                    PeriodName = piu.Period != null ? $"{piu.Period.Year} - {piu.Period.Term}" : "",
                    EmployeeName = piu.Ename,
                    EmployeeTitle = piu.PLSTX,
                    Department = piu.Orgtx,
                    PerNr = piu.PerNr,
                    Goals = employeeGoals.Select(g => new EmployeeGoalDto
                    {
                        Id = g.Id,
                        PeriodId = g.PeriodId,
                        PeriodName = piu.Period != null ? $"{piu.Period.Year} - {piu.Period.Term}" : "",
                        IndicatorId = g.IndicatorId,
                        IndicatorName = g.Indicator?.IndicatorName,
                        PeriodInUserId = g.PeriodInUserId,
                        GoalTitle = g.GoalTitle,
                        GoalDescription = g.GoalDescription,
                        TargetValue = g.TargetValue,
                        TargetUnit = g.TargetUnit,
                        Weight = g.Weight,
                        StartDate = g.StartDate,
                        EndDate = g.EndDate,
                        Status = g.Status,
                        StatusText = g.Status switch
                        {
                            GoalStatus.PendingSecondApproval => "2. Üst Yönetici Onayında",
                            GoalStatus.Approved => "Onaylandı",
                            GoalStatus.Rejected => "Reddedildi",
                            _ => "Bilinmiyor"
                        },
                        ApprovalDate = g.ApprovalDate,
                        FirstApprovalDate = g.FirstApprovalDate,
                        SecondApprovalDate = g.SecondApprovalDate,
                        ManagerNote = g.ManagerNote,
                        SecondManagerNote = g.SecondManagerNote,
                        EmployeeNote = g.EmployeeNote,
                        RejectionCount = g.RejectionCount,
                        IsActive = g.IsActive
                    }).OrderBy(g => g.GoalTitle).ToList(),
                    TotalWeight = employeeGoals.Sum(g => g.Weight),
                    CanApprove = canApprove
                };
            }).Where(s => s.Goals.Any()).ToList();

            var pendingCount = subordinateGoals.Count(s => s.CanApprove);

            return new GetSecondLevelSubordinateGoalsResponse
            {
                Subordinates = subordinateGoals,
                PendingApprovalCount = pendingCount
            };
        }
    }
}
