using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllEvaluationPeriods
{
    public class GetAllEvaluationPeriodHandler(IUnitOfWork<IAppDbContext> unitOfWork,
       IUserContextService userContextService,
      IMapper mapper,
      ILogger<GetAllEvaluationPeriodHandler> logger) :
        BaseHandler<IAppDbContext, GetAllEvaluationPeriodHandler>(unitOfWork, mapper, logger),
      IRequestHandler<GetAllEvaluationPeriodRequest, List<GetAllEvaluationPeriodResponse>>
    {
        public async Task<List<GetAllEvaluationPeriodResponse>> Handle(GetAllEvaluationPeriodRequest request, CancellationToken cancellationToken)
        {
            var evaluations = await unitOfWork.GetAppReadRepository<PotentialEvaluation>()
            .GetAllAsync(include: x => x.Include(p => p.Period));


            var yearPeriods = evaluations
                 .GroupBy(pe => pe.Period.Year)
                 .Select(g => g.First()) //bir tane 
                 .OrderBy(x => x.Period.Year)
                 .Select(pe => new GetAllEvaluationPeriodResponse
                 {
                     Year = pe.Period.Year,
                     PeriodId = pe.Period.Id,
                     Term = pe.Period.Term,
                 })
                 .ToList();

            return yearPeriods;

            
        }
    }
}
