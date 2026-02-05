using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create
{
    public class CreatePotentialEvaluationRequest : IRequest<CreatePotentialEvaluationResponse>
    {
        public Guid PeriodId { get; set; }
        public List<PotentialEvaluationDto> CriteriaList { get; set; }
    }
}
