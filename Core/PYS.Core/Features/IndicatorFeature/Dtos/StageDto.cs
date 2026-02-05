using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.IndicatorFeature.Dtos
{
    public class StageDto
    {
        public string StageDesc { get; set; }
        public decimal StageLower { get; set; }
        public decimal StageTop { get; set; }
    }
}
