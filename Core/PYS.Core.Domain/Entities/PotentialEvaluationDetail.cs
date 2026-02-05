using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class PotentialEvaluationDetail : EntityBase
    {
        public Guid PotentialEvaluationId { get; set; }
        public string CriteriaDetailName { get; set; }
        public bool CriteriaDetailStatus { get; set; }

        public PotentialEvaluation PotentialEvaluation { get; set; }
    }
}
