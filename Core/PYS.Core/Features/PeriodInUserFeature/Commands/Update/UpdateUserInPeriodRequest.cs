using MediatR;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Commands.Update
{
    public class UpdateUserInPeriodRequest : IRequest<UpdateUserInPeriodResponse>
    {
        public Guid Id { get; set; }
        public int PerNr { get; set; }
        public int SID { get; set; }
        public string PLSTX { get; set; }
        public int OID { get; set; }
        public string Orgtx { get; set; }
        public string MMail { get; set; }
        public int MPernr { get; set; }
        public int Level { get; set; }

    }
}
