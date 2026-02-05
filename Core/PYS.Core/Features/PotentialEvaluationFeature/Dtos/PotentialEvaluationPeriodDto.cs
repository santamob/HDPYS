using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos
{
    public class PotentialEvaluationPeriodDto
    {
        public Guid PeriodId { get; set; }
        public int Year { get; set; }

        public string Term { get; set; }
    }
}
