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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoalsSecondLevel
{
    /// <summary>
    /// 2. Üst yönetici onay handler'ı.
    /// PendingSecondApproval -> Approved geçişini yapar.
    /// Yetki doğrulama: Astın yöneticisinin PeriodInUser.MPernr == currentUser.RegistrationNumber
    /// </summary>
    public class ApproveGoalsSecondLevelCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<ApproveGoalsSecondLevelCommandHandler> logger,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, ApproveGoalsSecondLevelCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<ApproveGoalsSecondLevelCommandRequest, ApproveGoalsSecondLevelCommandResponse>
    {
        public async Task<ApproveGoalsSecondLevelCommandResponse> Handle(ApproveGoalsSecondLevelCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
                if (appUser == null)
                {
                    return new ApproveGoalsSecondLevelCommandResponse
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
                    return new ApproveGoalsSecondLevelCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Çalışan kaydı bulunamadı." }
                    };
                }

                // Yetki doğrulama: Astın yöneticisinin PeriodInUser.MPernr == currentUser.RegistrationNumber
                // Önce astın doğrudan yöneticisini bul
                bool isAuthorized = false;
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

                var authCheck = await goalRules.ManagerShouldBeAuthorized(isAuthorized);
                if (!authCheck.IsSuccess)
                {
                    return new ApproveGoalsSecondLevelCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { authCheck.ErrorMessage! }
                    };
                }

                // PendingSecondApproval durumundaki hedefleri al
                var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodInUserId == request.PeriodInUserId
                                      && g.PeriodId == request.PeriodId
                                      && g.IsActive
                                      && g.Status == GoalStatus.PendingSecondApproval))
                    .ToList();

                if (!goals.Any())
                {
                    return new ApproveGoalsSecondLevelCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Onaylanacak hedef bulunamadı." }
                    };
                }

                // Durum kontrolü
                var statusCheck = await goalRules.GoalsShouldBePendingSecondApproval(goals.Select(g => g.Status));
                if (!statusCheck.IsSuccess)
                {
                    return new ApproveGoalsSecondLevelCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { statusCheck.ErrorMessage! }
                    };
                }

                // Hedefleri Approved durumuna al
                foreach (var goal in goals)
                {
                    goal.Status = GoalStatus.Approved;
                    goal.SecondApprovalDate = DateTime.UtcNow;
                    goal.SecondManagerNote = request.ManagerNote;
                    // Onaylayan 2. yöneticiyi kesinleştir
                    goal.ApprovedBySecondManagerId = appUser.Id;
                    await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                }

                await unitOfWork.SaveAsync();

                return new ApproveGoalsSecondLevelCommandResponse
                {
                    Success = true,
                    Message = $"{goals.Count} hedef üst yönetici tarafından onaylandı.",
                    ApprovedCount = goals.Count
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedefler üst yönetici tarafından onaylanırken hata oluştu.");
                return new ApproveGoalsSecondLevelCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedefler onaylanırken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
