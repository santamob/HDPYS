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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal
{
    /// <summary>
    /// Hedef güncelleme komutu handler'ı.
    /// Sadece Draft veya Rejected durumundaki hedefler güncellenebilir.
    /// </summary>
    public class UpdateGoalCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<UpdateGoalCommandHandler> logger,
        UpdateGoalCommandValidator validator,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, UpdateGoalCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<UpdateGoalCommandRequest, UpdateGoalCommandResponse>
    {
        public async Task<UpdateGoalCommandResponse> Handle(UpdateGoalCommandRequest request, CancellationToken cancellationToken)
        {
            // Validasyon
            var validationResult = await validator.ValidateAsync(request, cancellationToken);
            if (!validationResult.IsValid)
            {
                return new UpdateGoalCommandResponse
                {
                    Success = false,
                    Errors = validationResult.Errors.Select(e => e.ErrorMessage).ToList()
                };
            }

            try
            {
                var employeeId = userContextService.UserId;

                // Hedefi bul
                var goal = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAsync(g => g.Id == request.Id && g.EmployeeId == employeeId && g.IsActive);

                // Hedef mevcut mu?
                var existCheck = await goalRules.GoalShouldExist(goal);
                if (!existCheck.IsSuccess)
                {
                    return new UpdateGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { existCheck.ErrorMessage! }
                    };
                }

                // Düzenlenebilir durumda mı?
                var editableCheck = await goalRules.GoalShouldBeEditableStatus(goal!.Status);
                if (!editableCheck.IsSuccess)
                {
                    return new UpdateGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { editableCheck.ErrorMessage! }
                    };
                }

                // Ağırlık kontrolü - mevcut hedefin ağırlığını çıkar, yeni ağırlığı ekle
                var allGoals = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodId == goal.PeriodId
                                      && g.EmployeeId == employeeId
                                      && g.Id != request.Id
                                      && g.IsActive);

                var totalWeightExcludingCurrent = allGoals.Sum(g => g.Weight);
                var weightCheck = await goalRules.TotalWeightShouldNotExceed100OnUpdate(totalWeightExcludingCurrent, request.Weight);
                if (!weightCheck.IsSuccess)
                {
                    return new UpdateGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { weightCheck.ErrorMessage! }
                    };
                }

                // Güncelle
                goal.GoalTitle = request.GoalTitle;
                goal.GoalDescription = request.GoalDescription;
                goal.TargetValue = request.TargetValue;
                goal.TargetUnit = request.TargetUnit;
                goal.Weight = request.Weight;
                goal.StartDate = request.StartDate;
                goal.EndDate = request.EndDate;
                goal.EmployeeNote = request.EmployeeNote;

                // Reddedilmiş ise tekrar taslak durumuna al
                if (goal.Status == GoalStatus.Rejected)
                {
                    goal.Status = GoalStatus.Draft;
                }

                await unitOfWork.GetAppWriteRepository<EmployeeGoal>().UpdateAsync(goal);
                await unitOfWork.SaveAsync();

                return new UpdateGoalCommandResponse
                {
                    Success = true,
                    Message = "Hedef başarıyla güncellendi."
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedef güncellenirken hata oluştu. Id: {GoalId}", request.Id);
                return new UpdateGoalCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedef güncellenirken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
