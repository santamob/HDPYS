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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.SubmitForApproval
{
    /// <summary>
    /// Hedefleri yönetici onayına gönderme handler'ı.
    /// PeriodInUser tablosundaki MPernr kolonundan yönetici bilgisi çekilir.
    /// Yöneticinin AppUser kaydı RegistrationNumber == MPernr eşleşmesiyle bulunur.
    /// </summary>
    public class SubmitForApprovalCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
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
                // Giriş yapan kullanıcının SAP PerNr bilgisini al
                var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
                if (appUser == null)
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Kullanıcı bilgisi bulunamadı." }
                    };
                }

                var userEmail = appUser.Email!.ToUpperInvariant();

                // PeriodInUser kaydını bul
                var periodInUser = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                    .GetAsync(p => p.Mail.ToUpper() == userEmail && p.PeriodId == request.PeriodId && p.IsActive);

                if (periodInUser == null)
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Bu dönem için kayıtlı çalışan bilginiz bulunamadı." }
                    };
                }

                // Çalışanın bu dönemdeki gönderilebilir hedeflerini al
                var goals = (await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodInUserId == periodInUser.Id
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
                    .GetAllAsync(g => g.PeriodInUserId == periodInUser.Id && g.IsActive);

                var totalWeight = allActiveGoals.Sum(g => g.Weight);
                if (totalWeight != 100)
                {
                    return new SubmitForApprovalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { $"Hedefler onaya gönderilebilmesi için toplam ağırlık %100 olmalıdır. Mevcut toplam: %{totalWeight}" }
                    };
                }

                // Yönetici bilgisini PeriodInUser.MPernr üzerinden çöz
                Guid? managerId = null;
                if (periodInUser.MPernr.HasValue)
                {
                    var allUsers = userManager.Users;
                    var managerUser = await allUsers
                        .FirstOrDefaultAsync(u => u.RegistrationNumber == periodInUser.MPernr.Value, cancellationToken);
                    managerId = managerUser?.Id;
                }

                // Hedefleri PendingFirstApproval durumuna al ve yöneticiyi ata
                foreach (var goal in goals)
                {
                    goal.Status = GoalStatus.PendingFirstApproval;
                    if (managerId.HasValue)
                    {
                        goal.ApprovedByManagerId = managerId.Value;
                    }
                    // Yeniden gönderimde 2. yönetici alanlarını temizle
                    goal.ApprovedBySecondManagerId = null;
                    goal.FirstApprovalDate = null;
                    goal.SecondApprovalDate = null;
                    goal.SecondManagerNote = null;
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
