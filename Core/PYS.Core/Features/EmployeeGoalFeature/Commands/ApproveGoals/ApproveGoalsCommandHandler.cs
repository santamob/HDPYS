using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoals
{
    /// <summary>
    /// 1. Yönetici onay handler'ı.
    /// PendingFirstApproval -> PendingSecondApproval geçişini yapar.
    /// 2. üst yönetici bulunamazsa otomatik olarak Approved durumuna alır.
    /// </summary>
    public class ApproveGoalsCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<ApproveGoalsCommandHandler> logger,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, ApproveGoalsCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<ApproveGoalsCommandRequest, ApproveGoalsCommandResponse>
    {
        public async Task<ApproveGoalsCommandResponse> Handle(ApproveGoalsCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
                if (appUser == null)
                {
                    return new ApproveGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Kullanıcı bilgisi bulunamadı." }
                    };
                }

                // PeriodInUser kaydını bul ve yetki doğrula
                var periodInUser = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                    .GetAsync(p => p.Id == request.PeriodInUserId && p.IsActive);

                if (periodInUser == null)
                {
                    return new ApproveGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Çalışan kaydı bulunamadı." }
                    };
                }

                // Yetki kontrolü: Astın MPernr'ı == mevcut kullanıcının RegistrationNumber'ı
                var authCheck = await goalRules.ManagerShouldBeAuthorized(
                    periodInUser.MPernr == appUser.RegistrationNumber);
                if (!authCheck.IsSuccess)
                {
                    return new ApproveGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { authCheck.ErrorMessage! }
                    };
                }

                // PendingFirstApproval durumundaki hedefleri al
                var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodInUserId == request.PeriodInUserId
                                      && g.PeriodId == request.PeriodId
                                      && g.IsActive
                                      && g.Status == GoalStatus.PendingFirstApproval))
                    .ToList();

                if (!goals.Any())
                {
                    return new ApproveGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Onaylanacak hedef bulunamadı." }
                    };
                }

                // Durum kontrolü
                var statusCheck = await goalRules.GoalsShouldBePendingFirstApproval(goals.Select(g => g.Status));
                if (!statusCheck.IsSuccess)
                {
                    return new ApproveGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { statusCheck.ErrorMessage! }
                    };
                }

                // 2. üst yöneticiyi bul: 1. yöneticinin kendi PeriodInUser kaydındaki MPernr -> AppUser
                var managerPeriodInUser = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                    .GetAsync(p => p.PerNr == appUser.RegistrationNumber
                                   && p.PeriodId == request.PeriodId
                                   && p.IsActive);

                Guid? secondManagerId = null;
                bool hasSecondManager = false;

                if (managerPeriodInUser?.MPernr != null)
                {
                    var secondManagerUser = await userManager.Users
                        .FirstOrDefaultAsync(u => u.RegistrationNumber == managerPeriodInUser.MPernr.Value, cancellationToken);
                    if (secondManagerUser != null)
                    {
                        secondManagerId = secondManagerUser.Id;
                        hasSecondManager = true;
                    }
                }

                // Hedefleri güncelle
                foreach (var goal in goals)
                {
                    goal.ApprovedByManagerId = appUser.Id;
                    goal.FirstApprovalDate = DateTime.UtcNow;
                    goal.ApprovalDate = DateTime.UtcNow;
                    goal.ManagerNote = request.ManagerNote;

                    if (hasSecondManager)
                    {
                        // 2. üst yönetici var -> PendingSecondApproval
                        goal.Status = GoalStatus.PendingSecondApproval;
                        goal.ApprovedBySecondManagerId = secondManagerId;
                    }
                    else
                    {
                        // 2. üst yönetici yok -> otomatik final onay
                        goal.Status = GoalStatus.Approved;
                        goal.SecondApprovalDate = DateTime.UtcNow;
                    }

                    await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                }

                await unitOfWork.SaveAsync();

                var message = hasSecondManager
                    ? $"{goals.Count} hedef onaylandı ve üst yönetici onayına gönderildi."
                    : $"{goals.Count} hedef onaylandı (üst yönetici bulunamadığı için otomatik onaylandı).";

                return new ApproveGoalsCommandResponse
                {
                    Success = true,
                    Message = message,
                    ApprovedCount = goals.Count
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedefler onaylanırken hata oluştu.");
                return new ApproveGoalsCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedefler onaylanırken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
