using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetPeriodById
{
    public class GetPeriodByIdResponse
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; }
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public bool HasStaging { get; set; }

        public List<Guid> FormTypeIds { get; set; }
        public List<Guid> LocationIds { get; set; }
    }
}
