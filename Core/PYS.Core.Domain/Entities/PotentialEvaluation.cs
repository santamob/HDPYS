using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class PotentialEvaluation : EntityBase
    {
        public string CriteriaName { get; set; }
        public decimal MinValue { get; set; }
        public decimal MaxValue { get; set; }

        public Guid PeriodId { get; set; }
        public  Periods Period { get; set; }

        public  ICollection<PotentialEvaluationDetail> PotentialEvaluationDetails { get; set; } =new List<PotentialEvaluationDetail>();

    }
}
