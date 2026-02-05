using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos
{
    public class PotentialEvaluationDto
    {
        public string CriteriaName { get; set; }
        public int MinValue { get; set; }
        public int MaxValue { get; set; }
        public List<PotentialEvaluationDetailDto> CriteriaDetails { get; set; }
    }

}
