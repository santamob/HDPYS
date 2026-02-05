using MediatR;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Principal;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetUserInPeriodById
{
    public class GetUserInPeriodByIdRequest : IRequest<GetUserInPeriodByIdResponse>
    {
        public Guid Id{ get; set; }
    }
}
