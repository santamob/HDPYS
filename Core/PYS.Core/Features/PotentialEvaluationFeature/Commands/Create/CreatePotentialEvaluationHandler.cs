using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.IndicatorFeature.Commands.Create;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create
{
    public class CreatePotentialEvaluationHandler(
      IUnitOfWork<IAppDbContext> unitOfWork,
       IUserContextService userContextService,
      IMapper mapper,
      ILogger<CreatePotentialEvaluationHandler> logger
  ) : BaseHandler<IAppDbContext, CreatePotentialEvaluationHandler>(unitOfWork, mapper, logger),
      IRequestHandler<CreatePotentialEvaluationRequest, CreatePotentialEvaluationResponse>
    {
        public async Task<CreatePotentialEvaluationResponse> Handle(CreatePotentialEvaluationRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var existing = await unitOfWork.GetAppReadRepository<PotentialEvaluation>()
                        .GetAsync(x => x.PeriodId == request.PeriodId);

                if (existing != null)
                    throw new Exception("Bu döneme ait bir potansiyel değerlendirme zaten mevcut.");

                foreach (var criteria in request.CriteriaList)
                {
                    var entity = mapper.Map<PotentialEvaluation>(criteria);
                    entity.PeriodId = request.PeriodId;

                    entity.CreatedIp = userContextService.IpAddress;
                    entity.AppUserCreatedId = userContextService.UserId;

                    entity.PotentialEvaluationDetails = criteria.CriteriaDetails.Select(detail => new PotentialEvaluationDetail
                    {
                        CriteriaDetailName = detail.CriteriaDetailName,
                        CriteriaDetailStatus = detail.CriteriaDetailStatus,
                        CreatedIp = userContextService.IpAddress,
                        AppUserCreatedId = userContextService.UserId
                    }).ToList();

                    await unitOfWork.GetAppWriteRepository<PotentialEvaluation>().AddAsync(entity);
                }

                await unitOfWork.SaveAsync();

                return new CreatePotentialEvaluationResponse
                {
                    IsSuccess = true,
                    Message = "Kriter başarıyla eklendi."
                };
            }
            catch (Exception ex)
            {
                return new CreatePotentialEvaluationResponse
                {
                    IsSuccess = false,
                    Message = $"Hata oluştu: {ex.Message}"
                };
            }
        }
    }
   

}
