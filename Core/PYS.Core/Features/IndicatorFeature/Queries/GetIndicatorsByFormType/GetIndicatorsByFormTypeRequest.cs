using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorsByFormType
{
    public class GetIndicatorsByFormTypeRequest : IRequest<List<GetIndicatorsByFormTypeResponse>>
    {
        public Guid FormTypeId { get; set; }
    }
}
