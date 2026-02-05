using MediatR;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Create;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodsFeature.Commands.Update
{
    public class UpdatePeriodCommandRequest : IRequest<UpdatePeriodCommandResponse>
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; }
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public bool HasStaging { get; set; }

        public List<Guid> FormTypeIds { get; set; } = new();
        public List<Guid> LocationIds { get; set; } = new();
    }
}
