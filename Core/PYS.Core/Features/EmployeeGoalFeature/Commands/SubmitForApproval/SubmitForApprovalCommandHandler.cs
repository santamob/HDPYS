using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.SubmitForApproval
{
    /// <summary>
    /// Hedefleri yönetici onayına gönderme handler'ı.
    /// PeriodInUser tablosundaki MPernr kolonundan yönetici bilgisi çekilir.
    /// </summary>
    public class SubmitForApprovalCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<SubmitForApprovalCommandHandler> logger,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, SubmitForApprovalCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<SubmitForApprovalCommandRequest, SubmitForApprovalCommandResponse>
    {
        public async Task<SubmitForApprovalCommandResponse> Handle(SubmitForApprovalCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var employeeId = userContextService.UserId;

                // Çalışanın bu dönemdeki gönderilebilir hedeflerini al
                var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodId == request.PeriodId
                                      && g.EmployeeId == employeeId
                                      && g.IsActive
                                      && (g.Status == GoalStatus.Draft || g.Status == GoalStatus.Rejected)))
                    .ToList();

                if (!goals.Any())
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Onaya gönderilebilecek hedef bulunamadı." }
                    };
                }

                // Durum kontrolü
                var submittableCheck = await goalRules.GoalsShouldBeSubmittable(goals.Select(g => g.Status));
                if (!submittableCheck.IsSuccess)
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { submittableCheck.ErrorMessage! }
                    };
                }

                // Toplam ağırlık kontrolü - tüm aktif hedeflerin toplamı %100 olmalı
                var allActiveGoals = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodId == request.PeriodId
                                      && g.EmployeeId == employeeId
                                      && g.IsActive);

                var totalWeight = allActiveGoals.Sum(g => g.Weight);
                if (totalWeight != 100)
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { $"Hedefler onaya gönderilebilmesi için toplam ağırlık %100 olmalıdır. Mevcut toplam: %{totalWeight}" }
                    };
                }

                // Hedefleri PendingApproval durumuna al
                foreach (var goal in goals)
                {
                    goal.Status = GoalStatus.PendingApproval;
                    await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                }

                await unitOfWork.SaveAsync();

                return new SubmitForApprovalCommandResponse
                {
                    Success = true,
                    Message = $"{goals.Count} hedef yönetici onayına gönderildi.",
                    SubmittedCount = goals.Count
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedefler onaya gönderilirken hata oluştu.");
                return new SubmitForApprovalCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedefler onaya gönderilirken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
