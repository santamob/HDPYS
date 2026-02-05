using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.PeriodsFeature.Commands.Delete
{
    public class DeletePeriodCommandRequest : IRequest<DeletePeriodCommandResponse>
    {
        public Guid PeriodId { get; set; }
    }
}
