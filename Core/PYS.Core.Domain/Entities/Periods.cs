using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Periods : EntityBase
    {
        public int Year { get; set; }

        public string Term { get; set; } // Yıl Sonu veya Ara Dönem

        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }

        public bool HasStaging { get; set; }

        public ICollection<FormTypesPeriods> FormTypesPeriods { get; set; } = new List<FormTypesPeriods>();
        public ICollection<LocationPeriods> LocationPeriods { get; set; } = new List<LocationPeriods>();
        public ICollection<PotentialEvaluation> PotentialEvaluation { get; set; } = new List<PotentialEvaluation>();

        public ICollection<PeriodInUser> PeriodInUser { get; set; } = new List<PeriodInUser>();
    }
}
