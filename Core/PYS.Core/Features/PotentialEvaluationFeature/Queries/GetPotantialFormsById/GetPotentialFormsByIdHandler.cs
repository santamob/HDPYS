using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById
{
    public class GetPotentialFormsByIdHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetPotentialFormsByIdHandler> logger)
        : BaseHandler<IAppDbContext, GetPotentialFormsByIdHandler>(unitOfWork, mapper, logger),
         IRequestHandler<GetPotentialFormsByIdRequest, List<GetPotentialFormsByIdResponse>>
    {
        public async Task<List<GetPotentialFormsByIdResponse>> Handle(GetPotentialFormsByIdRequest request, CancellationToken cancellationToken)
        {
            var evaluations = await unitOfWork.GetAppReadRepository<PotentialEvaluation>()
                        .GetAllAsync(
                            x => x.PeriodId == request.PeriodId,
                            include: x => x.Include(e => e.PotentialEvaluationDetails)
                        );
           
            return mapper.Map<List<GetPotentialFormsByIdResponse>>(evaluations);
        }
    }
}
