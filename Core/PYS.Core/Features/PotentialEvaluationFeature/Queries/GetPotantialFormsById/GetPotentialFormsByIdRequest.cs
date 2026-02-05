using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById
{
    public class GetPotentialFormsByIdRequest : IRequest<List<GetPotentialFormsByIdResponse>>
    {
        public Guid PeriodId { get; set; }
    }
}
