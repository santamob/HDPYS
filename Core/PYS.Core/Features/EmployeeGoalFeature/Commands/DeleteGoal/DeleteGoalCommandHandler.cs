using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.DeleteGoal
{
    /// <summary>
    /// Hedef silme komutu handler'ı.
    /// Soft delete uygular (IsActive = false).
    /// PeriodInUser üzerinden sahiplik doğrulaması yapılır.
    /// </summary>
    public class DeleteGoalCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<DeleteGoalCommandHandler> logger,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, DeleteGoalCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<DeleteGoalCommandRequest, DeleteGoalCommandResponse>
    {
        public async Task<DeleteGoalCommandResponse> Handle(DeleteGoalCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                // Giriş yapan kullanıcının SAP PerNr bilgisini al
                var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
                if (appUser == null)
                {
                    return new DeleteGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Kullanıcı bilgisi bulunamadı." }
                    };
                }

                var goal = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAsync(g => g.Id == request.Id && g.IsActive,
                        include: x => x.Include(g => g.PeriodInUser));

                // Hedef mevcut mu?
                var existCheck = await goalRules.GoalShouldExist(goal);
                if (!existCheck.IsSuccess)
                {
                    return new DeleteGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { existCheck.ErrorMessage! }
                    };
                }

                // Sahiplik kontrolü - PeriodInUser.Mail eşleşmeli
                if (!string.Equals(goal!.PeriodInUser.Mail, appUser.Email, StringComparison.OrdinalIgnoreCase))
                {
                    return new DeleteGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { "Bu hedefe erişim yetkiniz bulunmamaktadır." }
                    };
                }

                // Sadece Draft durumunda silinebilir
                var draftCheck = await goalRules.GoalShouldBeDraftForDeletion(goal.Status);
                if (!draftCheck.IsSuccess)
                {
                    return new DeleteGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { draftCheck.ErrorMessage! }
                    };
                }

                // Soft delete
                goal.IsActive = false;
                await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                await unitOfWork.SaveAsync();

                return new DeleteGoalCommandResponse
                {
                    Success = true,
                    Message = "Hedef başarıyla silindi."
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedef silinirken hata oluştu. Id: {GoalId}", request.Id);
                return new DeleteGoalCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedef silinirken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
