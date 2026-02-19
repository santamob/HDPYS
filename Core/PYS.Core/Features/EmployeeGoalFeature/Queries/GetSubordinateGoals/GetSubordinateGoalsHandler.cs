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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSubordinateGoals
{
    /// <summary>
    /// 1. derece astların hedeflerini getiren handler.
    /// PeriodInUser.MPernr == currentUser.RegistrationNumber olan kayıtlar doğrudan astlardır.
    /// </summary>
    public class GetSubordinateGoalsHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<GetSubordinateGoalsHandler> logger
    ) : BaseHandler<IAppDbContext, GetSubordinateGoalsHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetSubordinateGoalsRequest, GetSubordinateGoalsResponse>
    {
        public async Task<GetSubordinateGoalsResponse> Handle(GetSubordinateGoalsRequest request, CancellationToken cancellationToken)
        {
            var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
            if (appUser == null)
            {
                return new GetSubordinateGoalsResponse();
            }

            var managerPerNr = appUser.RegistrationNumber;

            // 1. derece astları bul: PeriodInUser.MPernr == mevcut kullanıcının RegistrationNumber'ı
            var subordinatePeriodInUsers = (await unitOfWork.GetAppReadRepository<PeriodInUser>()
                .GetAllAsync(
                    predicate: p => p.MPernr == managerPerNr && p.IsActive,
                    include: x => x.Include(p => p.Period)))
                .ToList();

            if (request.PeriodId.HasValue)
            {
                subordinatePeriodInUsers = subordinatePeriodInUsers
                    .Where(p => p.PeriodId == request.PeriodId.Value).ToList();
            }

            if (!subordinatePeriodInUsers.Any())
            {
                return new GetSubordinateGoalsResponse();
            }

            var subordinatePiuIds = subordinatePeriodInUsers.Select(p => p.Id).ToList();

            // Astların hedeflerini çek
            var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                .GetAllAsync(
                    predicate: g => subordinatePiuIds.Contains(g.PeriodInUserId) && g.IsActive,
                    include: x => x.Include(g => g.Indicator!)))
                .ToList();

            // Çalışan bazında grupla
            var subordinateGoals = subordinatePeriodInUsers.Select(piu =>
            {
                var employeeGoals = goals.Where(g => g.PeriodInUserId == piu.Id).ToList();
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
                            GoalStatus.Draft => "Taslak",
                            GoalStatus.PendingFirstApproval => "1. Yonetici Onayinda",
                            GoalStatus.PendingSecondApproval => "2. Ust Yonetici Onayinda",
                            GoalStatus.Approved => "Onaylandi",
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
                    CanApprove = employeeGoals.Any() && employeeGoals.All(g => g.Status == GoalStatus.PendingFirstApproval)
                };
            }).Where(s => s.Goals.Any()).ToList();

            var pendingCount = subordinateGoals.Count(s => s.CanApprove);

            return new GetSubordinateGoalsResponse
            {
                Subordinates = subordinateGoals,
                PendingApprovalCount = pendingCount
            };
        }
    }
}
