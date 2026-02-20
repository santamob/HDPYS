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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.RejectGoals
{
    /// <summary>
    /// Hedef reddetme handler'ı.
    /// ApprovalLevel'a göre yetki doğrulama ve uygun durumdan red işlemi yapar.
    /// Level 1: PendingFirstApproval -> Rejected (1. yönetici)
    /// Level 2: PendingSecondApproval -> Rejected (2. üst yönetici)
    /// </summary>
    public class RejectGoalsCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<RejectGoalsCommandHandler> logger,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, RejectGoalsCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<RejectGoalsCommandRequest, RejectGoalsCommandResponse>
    {
        public async Task<RejectGoalsCommandResponse> Handle(RejectGoalsCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
                if (appUser == null)
                {
                    return new RejectGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Kullanıcı bilgisi bulunamadı." }
                    };
                }

                // Çalışanın PeriodInUser kaydını bul
                var periodInUser = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                    .GetAsync(p => p.Id == request.PeriodInUserId && p.IsActive);

                if (periodInUser == null)
                {
                    return new RejectGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Çalışan kaydı bulunamadı." }
                    };
                }

                // Yetki doğrulama
                bool isAuthorized = false;
                if (request.ApprovalLevel == 1)
                {
                    // 1. yönetici: Astın MPernr == mevcut kullanıcının RegistrationNumber
                    isAuthorized = periodInUser.MPernr == appUser.RegistrationNumber;
                }
                else if (request.ApprovalLevel == 2)
                {
                    // 2. üst yönetici: Astın yöneticisinin MPernr == mevcut kullanıcının RegistrationNumber
                    if (periodInUser.MPernr.HasValue)
                    {
                        var directManagerPiu = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                            .GetAsync(p => p.PerNr == periodInUser.MPernr.Value
                                           && p.PeriodId == request.PeriodId
                                           && p.IsActive);
                        if (directManagerPiu != null)
                        {
                            isAuthorized = directManagerPiu.MPernr == appUser.RegistrationNumber;
                        }
                    }
                }

                var authCheck = await goalRules.ManagerShouldBeAuthorized(isAuthorized);
                if (!authCheck.IsSuccess)
                {
                    return new RejectGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { authCheck.ErrorMessage! }
                    };
                }

                // Uygun durumdaki hedefleri al
                var targetStatus = request.ApprovalLevel == 1
                    ? GoalStatus.PendingFirstApproval
                    : GoalStatus.PendingSecondApproval;

                var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodInUserId == request.PeriodInUserId
                                      && g.PeriodId == request.PeriodId
                                      && g.IsActive
                                      && g.Status == targetStatus))
                    .ToList();

                if (!goals.Any())
                {
                    return new RejectGoalsCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Reddedilecek hedef bulunamadı." }
                    };
                }

                // Hedefleri Rejected durumuna al
                foreach (var goal in goals)
                {
                    goal.Status = GoalStatus.Rejected;
                    goal.RejectionCount++;

                    if (request.ApprovalLevel == 1)
                    {
                        goal.ManagerNote = request.ManagerNote;
                        // 1. yönetici reddinde onay tarihlerini temizle (çalışan yeniden düzenleyip gönderebilsin)
                        goal.ApprovalDate = null;
                        goal.FirstApprovalDate = null;
                        goal.ApprovedByManagerId = null;
                        goal.ApprovedBySecondManagerId = null;
                        goal.SecondApprovalDate = null;
                    }
                    else
                    {
                        goal.SecondManagerNote = request.ManagerNote;
                        // 2. yönetici reddinde yalnızca 2. onay alanlarını temizle
                        goal.ApprovedBySecondManagerId = null;
                        goal.SecondApprovalDate = null;
                    }

                    await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                }

                await unitOfWork.SaveAsync();

                var levelText = request.ApprovalLevel == 1 ? "1. Yönetici" : "2. Üst Yönetici";
                return new RejectGoalsCommandResponse
                {
                    Success = true,
                    Message = $"{goals.Count} hedef {levelText} tarafından reddedildi.",
                    RejectedCount = goals.Count
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedefler reddedilirken hata oluştu.");
                return new RejectGoalsCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedefler reddedilirken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
