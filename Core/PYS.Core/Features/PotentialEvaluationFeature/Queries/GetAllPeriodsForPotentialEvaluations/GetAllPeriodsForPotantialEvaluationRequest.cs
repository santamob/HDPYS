using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations
{
    public class GetAllPeriodsForPotantialEvaluationRequest : IRequest<IList<GetAllPeriodsForPotantialEvaluationResponse>>
    {
    }
}
