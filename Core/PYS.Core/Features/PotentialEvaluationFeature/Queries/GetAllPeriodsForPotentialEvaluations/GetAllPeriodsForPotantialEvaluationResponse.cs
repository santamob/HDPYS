using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations
{
    public class GetAllPeriodsForPotantialEvaluationResponse
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; }
    }
}
