using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Location : EntityBase
    {
        public string Name { get; set; }
       // public ICollection<Periods> Periods { get; set; } = new List<Periods>();

        public ICollection<LocationPeriods> LocationPeriods { get; set; } = new List<LocationPeriods>();
    }
}
