using MediatR;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Commands.Create
{
    public class CreatePeriodInUserRequest : IRequest<CreatePeriodInUserResponse>
    {
        public Guid PeriodId { get; set; }
        public string PeriodText { get; set; }
        public List<PeriodInUserDto> Users { get; set; }
    }
}
