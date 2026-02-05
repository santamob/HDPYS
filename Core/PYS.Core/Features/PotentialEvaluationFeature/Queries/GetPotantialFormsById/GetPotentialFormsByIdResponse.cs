using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos;

namespace PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById
{
    public class GetPotentialFormsByIdResponse
    {
        public Guid Id { get; set; } 
        public string CriteriaName { get; set; }
        public int MinValue { get; set; }
        public int MaxValue { get; set; }

        public List<PotentialCriteriaDetailDto> CriteriaDetails { get; set; }
    }
}
