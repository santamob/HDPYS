using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations
{
    public class GetAllPeriodsForPotantialEvaluationHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, 
        ILogger<GetAllPeriodsForPotantialEvaluationHandler> logger) : BaseHandler<IAppDbContext, GetAllPeriodsForPotantialEvaluationHandler>(unitOfWork, mapper, logger), 
        IRequestHandler<GetAllPeriodsForPotantialEvaluationRequest, IList<GetAllPeriodsForPotantialEvaluationResponse>>
    {
        public async Task<IList<GetAllPeriodsForPotantialEvaluationResponse>> Handle(GetAllPeriodsForPotantialEvaluationRequest request, CancellationToken cancellationToken)
        {
            var periods = await unitOfWork.GetAppReadRepository<Periods>().GetAllAsync();

            return mapper.Map<List<GetAllPeriodsForPotantialEvaluationResponse>>(periods);
        }
    }
}
