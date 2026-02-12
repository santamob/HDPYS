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

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal
{
    /// <summary>
    /// Hedef oluşturma komutu handler'ı.
    /// Çalışanın seçtiği dönem için hedeflerini kaydeder.
    /// </summary>
    public class CreateGoalCommandHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<CreateGoalCommandHandler> logger,
        CreateGoalCommandValidator validator,
        EmployeeGoalRules goalRules
    ) : BaseHandler<IAppDbContext, CreateGoalCommandHandler>(unitOfWork, mapper, logger),
        IRequestHandler<CreateGoalCommandRequest, CreateGoalCommandResponse>
    {
        public async Task<CreateGoalCommandResponse> Handle(CreateGoalCommandRequest request, CancellationToken cancellationToken)
        {
            // Validasyon
            var validationResult = await validator.ValidateAsync(request, cancellationToken);
            if (!validationResult.IsValid)
            {
                return new CreateGoalCommandResponse
                {
                    Success = false,
                    Errors = validationResult.Errors.Select(e => e.ErrorMessage).ToList()
                };
            }

            try
            {
                var employeeId = userContextService.UserId;

                // Mevcut toplam ağırlığı hesapla (aynı dönem, aynı çalışan, aktif hedefler)
                var existingGoals = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                    .GetAllAsync(g => g.PeriodId == request.PeriodId
                                      && g.EmployeeId == employeeId
                                      && g.IsActive);

                var currentTotalWeight = existingGoals.Sum(g => g.Weight);
                var newTotalWeight = request.Goals.Sum(g => g.Weight);

                // Toplam ağırlık kontrolü
                var weightCheck = await goalRules.TotalWeightShouldNotExceed100(currentTotalWeight, newTotalWeight);
                if (!weightCheck.IsSuccess)
                {
                    return new CreateGoalCommandResponse
                    {
                        Success = false,
                        Errors = new List<string> { weightCheck.ErrorMessage! }
                    };
                }

                var createdIds = new List<Guid>();

                foreach (var goalItem in request.Goals)
                {
                    // Gösterge bazlı tekrar kontrolü
                    if (goalItem.IndicatorId.HasValue)
                    {
                        var existingGoal = await unitOfWork.GetAppReadRepository<EmployeeGoal>()
                            .GetAsync(g => g.PeriodId == request.PeriodId
                                          && g.EmployeeId == employeeId
                                          && g.IndicatorId == goalItem.IndicatorId
                                          && g.IsActive);

                        var duplicateCheck = await goalRules.GoalShouldNotBeDuplicate(existingGoal);
                        if (!duplicateCheck.IsSuccess)
                        {
                            return new CreateGoalCommandResponse
                            {
                                Success = false,
                                Errors = new List<string> { duplicateCheck.ErrorMessage! }
                            };
                        }
                    }

                    var goal = new EmployeeGoal
                    {
                        PeriodId = request.PeriodId,
                        IndicatorId = goalItem.IndicatorId,
                        EmployeeId = employeeId,
                        GoalTitle = goalItem.GoalTitle,
                        GoalDescription = goalItem.GoalDescription,
                        TargetValue = goalItem.TargetValue,
                        TargetUnit = goalItem.TargetUnit,
                        Weight = goalItem.Weight,
                        StartDate = goalItem.StartDate,
                        EndDate = goalItem.EndDate,
                        EmployeeNote = goalItem.EmployeeNote,
                        Status = GoalStatus.Draft,
                        RejectionCount = 0,
                        CreatedIp = userContextService.IpAddress,
                        AppUserCreatedId = userContextService.UserId
                    };

                    await unitOfWork.GetAppWriteRepository<EmployeeGoal>().AddAsync(goal);
                    createdIds.Add(goal.Id);
                }

                await unitOfWork.SaveAsync();

                return new CreateGoalCommandResponse
                {
                    Success = true,
                    Message = "Hedefler başarıyla kaydedildi.",
                    CreatedGoalIds = createdIds
                };
            }
            catch (Exception ex)
            {
                logger.LogError(ex, "Hedef oluşturulurken hata oluştu.");
                return new CreateGoalCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { "Hedef oluşturulurken beklenmeyen bir hata oluştu." }
                };
            }
        }
    }
}
