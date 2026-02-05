using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById
{
    public class GetIndicatorByIdRequest : IRequest<GetIndicatorByIdResponse>
    {
        public Guid Id { get; set; }
    }
}
