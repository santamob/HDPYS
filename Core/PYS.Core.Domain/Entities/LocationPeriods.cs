using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Entities
{
    public class LocationPeriods
    {
        public Guid PeriodsId { get; set; }
        public Periods Period { get; set; }

        public Guid LocationsId { get; set; }
        public Location Location { get; set; }
    }
}
