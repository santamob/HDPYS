using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetMyGoals
{
    /// <summary>
    /// Çalışanın kendi hedeflerini getiren handler.
    /// Dashboard istatistikleri ve filtreleme destekler.
    /// </summary>
    public class GetMyGoalsQueryHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<GetMyGoalsQueryHandler> logger
    ) : BaseHandler<IAppDbContext, GetMyGoalsQueryHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetMyGoalsQueryRequest, GetMyGoalsQueryResponse>
    {
        public async Task<GetMyGoalsQueryResponse> Handle(GetMyGoalsQueryRequest request, CancellationToken cancellationToken)
        {
            var employeeId = userContextService.UserId;

            // Tüm aktif hedefleri çek (istatistik hesabı için)
            var allGoals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                .GetAllAsync(
                    predicate: g => g.EmployeeId == employeeId && g.IsActive,
                    include: x => x.Include(g => g.Period).Include(g => g.Indicator!)))
                .ToList();

            // Dönem filtresi
            if (request.PeriodId.HasValue)
            {
                allGoals = allGoals.Where(g => g.PeriodId == request.PeriodId.Value).ToList();
            }

            // Durum filtresi (filtrelenmemiş liste üzerinden istatistik hesapla)
            var goalsForStats = allGoals;
            var filteredGoals = allGoals;
            if (request.Status.HasValue)
            {
                var statusFilter = (GoalStatus)request.Status.Value;
                filteredGoals = allGoals.Where(g => g.Status == statusFilter).ToList();
            }

            var goalDtos = filteredGoals.Select(g => new EmployeeGoalDto
            {
                Id = g.Id,
                PeriodId = g.PeriodId,
                PeriodName = g.Period != null ? $"{g.Period.Year} - {g.Period.Term}" : "",
                IndicatorId = g.IndicatorId,
                IndicatorName = g.Indicator?.IndicatorName,
                EmployeeId = g.EmployeeId,
                GoalTitle = g.GoalTitle,
                GoalDescription = g.GoalDescription,
                TargetValue = g.TargetValue,
                TargetUnit = g.TargetUnit,
                Weight = g.Weight,
                StartDate = g.StartDate,
                EndDate = g.EndDate,
                ActualValue = g.ActualValue,
                AchievementRate = g.AchievementRate,
                CalculatedScore = g.CalculatedScore,
                WeightedScore = g.WeightedScore,
                Status = g.Status,
                StatusText = g.Status switch
                {
                    GoalStatus.Draft => "Taslak",
                    GoalStatus.PendingApproval => "Onay Bekliyor",
                    GoalStatus.Approved => "Onaylandı",
                    GoalStatus.Rejected => "Reddedildi",
                    _ => "Bilinmiyor"
                },
                ApprovalDate = g.ApprovalDate,
                ManagerNote = g.ManagerNote,
                EmployeeNote = g.EmployeeNote,
                RejectionCount = g.RejectionCount,
                IsActive = g.IsActive
            }).OrderByDescending(g => g.Status == GoalStatus.Rejected)
              .ThenByDescending(g => g.Status == GoalStatus.Draft)
              .ThenBy(g => g.GoalTitle)
              .ToList();

            return new GetMyGoalsQueryResponse
            {
                Goals = goalDtos,
                TotalCount = goalsForStats.Count,
                DraftCount = goalsForStats.Count(g => g.Status == GoalStatus.Draft),
                PendingApprovalCount = goalsForStats.Count(g => g.Status == GoalStatus.PendingApproval),
                ApprovedCount = goalsForStats.Count(g => g.Status == GoalStatus.Approved),
                RejectedCount = goalsForStats.Count(g => g.Status == GoalStatus.Rejected),
                TotalWeight = goalsForStats.Sum(g => g.Weight)
            };
        }
    }
}
