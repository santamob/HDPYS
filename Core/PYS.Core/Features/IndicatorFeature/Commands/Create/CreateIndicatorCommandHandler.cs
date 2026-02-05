using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.IndicatorFeature.Commands.Create
{
    public class CreateIndicatorCommandHandler(
     IUnitOfWork<IAppDbContext> unitOfWork,
     IUserContextService userContextService,
     IMapper mapper,
     ILogger<CreateIndicatorCommandHandler> logger
 ) : BaseHandler<IAppDbContext, CreateIndicatorCommandHandler>(unitOfWork, mapper, logger),
     IRequestHandler<CreateIndicatorCommandRequest, CreateIndicatorCommandResponse>
    {
        public async Task<CreateIndicatorCommandResponse> Handle(CreateIndicatorCommandRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var indicator = new Indicator
                {
                    FormTypeId = request.FormTypeId,
                    FormTypeText = request.FormTypeText,
                    IndicatorName = request.IndicatorName,
                    IndicatorPeriodText = request.IndicatorPeriodText,
                    IndicatorPeriod = request.IndicatorPeriod,
                    IndicatorDesc = request.IndicatorDesc,
                    IndicatorDetailDesc = request.IndicatorDetailDesc,
                    IndicatorCategory = request.IndicatorCategory,
                    IndicatorPlannedDesc = request.IndicatorPlannedDesc,
                    IndicatorRealizedDesc = request.IndicatorRealizedDesc,
                    IndicatorResultDesc = request.IndicatorResultDesc,
                    DataSource = request.IndicatorDataSource,
                    DataSourceText = request.IndicatorDataSource.ToString(), 
                    DataCalculation = request.IndicatorDataCalculation,
                    DataCalculationText = request.IndicatorDataCalculation.ToString(),
                    EvaluationType = request.IndicatorEvaluationType,
                    EvaluationTypeText = request.IndicatorEvaluationType.ToString(),
                    IndicatorKminDesc = request.IndicatorKmaxDesc,
                    IndicatorKmaxDesc = request.IndicatorKmaxDesc,
                    CreatedIp = userContextService.IpAddress,
                    AppUserCreatedId = userContextService.UserId,
          
                 
                };

                await unitOfWork.GetAppWriteRepository<Indicator>().AddAsync(indicator);

                // Stages tablosuna ekleme
                if (request.Stages != null && request.Stages.Any())
                {
                    var stages = request.Stages.Select(stage => new Stage
                    {
                        IndicatorId = indicator.Id,
                        StageDesc = stage.StageDesc,
                        StageLower = stage.StageLower,
                        StageTop = stage.StageTop,
                        CreatedIp = userContextService.IpAddress,
                        AppUserCreatedId = userContextService.UserId,
                    }).ToList();

                    await unitOfWork.GetAppWriteRepository<Stage>().AddRangeAsync(stages);
                }

                await unitOfWork.SaveAsync();

                return new CreateIndicatorCommandResponse
                {
                    IsSuccess = true,
                    Message = "Gösterge başarıyla eklendi."
                };
            }
            catch (Exception ex)
            {
               // logger.LogError(ex, "Gösterge ekleme sırasında hata oluştu.");
                return new CreateIndicatorCommandResponse
                {
                    IsSuccess = false,
                    Message = "Gösterge eklenirken bir hata oluştu."
                };
            }
        }
    }

}
