using MediatR;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetPeriodInUser
{
    public class GetPeriodInUserRequest : IRequest<IList<PeriodInUserDto>>
    {
         public Guid PeriodId { get; set; }
    }
}
