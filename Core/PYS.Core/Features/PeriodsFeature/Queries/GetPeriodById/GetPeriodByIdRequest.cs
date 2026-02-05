using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetPeriodById
{
    public class GetPeriodByIdRequest : IRequest<GetPeriodByIdResponse>
    {
        public Guid Id { get; set; }
    }
}
