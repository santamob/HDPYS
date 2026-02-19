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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetGoalById
{
    /// <summary>
    /// Tek bir hedefin detayını getiren handler.
    /// PeriodInUser üzerinden sahiplik doğrulaması yapılır.
    /// </summary>
    public class GetGoalByIdHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<GetGoalByIdHandler> logger
    ) : BaseHandler<IAppDbContext, GetGoalByIdHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetGoalByIdRequest, EmployeeGoalDto?>
    {
        public async Task<EmployeeGoalDto?> Handle(GetGoalByIdRequest request, CancellationToken cancellationToken)
        {
            // Giriş yapan kullanıcının SAP PerNr bilgisini al
            var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
            if (appUser == null) return null;

            var userEmail = appUser.Email!.ToUpperInvariant();

            var goal = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                .GetAsync(g => g.Id == request.Id && g.IsActive,
                    include: x => x.Include(g => g.Period).Include(g => g.Indicator!).Include(g => g.PeriodInUser));

            if (goal == null) return null;

            // Sahiplik kontrolü - PeriodInUser.Mail eşleşmeli
            if (!string.Equals(goal.PeriodInUser.Mail, appUser.Email, StringComparison.OrdinalIgnoreCase)) return null;

            return new EmployeeGoalDto
            {
                Id = goal.Id,
                PeriodId = goal.PeriodId,
                PeriodName = goal.Period != null ? $"{goal.Period.Year} - {goal.Period.Term}" : "",
                IndicatorId = goal.IndicatorId,
                IndicatorName = goal.Indicator?.IndicatorName,
                PeriodInUserId = goal.PeriodInUserId,
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
                    GoalStatus.PendingFirstApproval => "1. Yönetici Onayında",
                    GoalStatus.PendingSecondApproval => "2. Üst Yönetici Onayında",
                    GoalStatus.Approved => "Onaylandı",
                    GoalStatus.Rejected => "Reddedildi",
                    _ => "Bilinmiyor"
                },
                ApprovalDate = goal.ApprovalDate,
                FirstApprovalDate = goal.FirstApprovalDate,
                SecondApprovalDate = goal.SecondApprovalDate,
                ApprovedBySecondManagerId = goal.ApprovedBySecondManagerId,
                ManagerNote = goal.ManagerNote,
                SecondManagerNote = goal.SecondManagerNote,
                EmployeeNote = goal.EmployeeNote,
                RejectionCount = goal.RejectionCount,
                IsActive = goal.IsActive
            };
        }
    }
}
