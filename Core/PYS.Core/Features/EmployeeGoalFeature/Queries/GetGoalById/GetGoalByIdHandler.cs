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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetGoalById
{
    /// <summary>
    /// Tek bir hedefin detayını getiren handler
    /// </summary>
    public class GetGoalByIdHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<GetGoalByIdHandler> logger
    ) : BaseHandler<IAppDbContext, GetGoalByIdHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetGoalByIdRequest, EmployeeGoalDto?>
    {
        public async Task<EmployeeGoalDto?> Handle(GetGoalByIdRequest request, CancellationToken cancellationToken)
        {
            var employeeId = userContextService.UserId;

            var goal = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                .GetAsync(g => g.Id == request.Id && g.EmployeeId == employeeId && g.IsActive,
                    include: x => x.Include(g => g.Period).Include(g => g.Indicator!));

            if (goal == null)
                return null;

            return new EmployeeGoalDto
            {
                Id = goal.Id,
                PeriodId = goal.PeriodId,
                PeriodName = goal.Period != null ? $"{goal.Period.Year} - {goal.Period.Term}" : "",
                IndicatorId = goal.IndicatorId,
                IndicatorName = goal.Indicator?.IndicatorName,
                EmployeeId = goal.EmployeeId,
                GoalTitle = goal.GoalTitle,
                GoalDescription = goal.GoalDescription,
                TargetValue = goal.TargetValue,
                TargetUnit = goal.TargetUnit,
                Weight = goal.Weight,
                StartDate = goal.StartDate,
                EndDate = goal.EndDate,
                ActualValue = goal.ActualValue,
                AchievementRate = goal.AchievementRate,
                CalculatedScore = goal.CalculatedScore,
                WeightedScore = goal.WeightedScore,
                Status = goal.Status,
                StatusText = goal.Status switch
                {
                    GoalStatus.Draft => "Taslak",
                    GoalStatus.PendingApproval => "Onay Bekliyor",
                    GoalStatus.Approved => "Onaylandı",
                    GoalStatus.Rejected => "Reddedildi",
                    _ => "Bilinmiyor"
                },
                ApprovalDate = goal.ApprovalDate,
                ManagerNote = goal.ManagerNote,
                EmployeeNote = goal.EmployeeNote,
                RejectionCount = goal.RejectionCount,
                IsActive = goal.IsActive
            };
        }
    }
}
