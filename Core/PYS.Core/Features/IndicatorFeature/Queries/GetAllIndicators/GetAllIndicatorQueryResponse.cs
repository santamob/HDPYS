using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators
{
    public class GetAllIndicatorQueryResponse
    {
        public Guid Id { get; set; }
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }

        public string IndicatorName { get; set; }
        public string IndicatorPeriod { get; set; }
        public string IndicatorPeriodText { get; set; }
        public string IndicatorDesc { get; set; }
        public string IndicatorDetailDesc { get; set; }
        public string? IndicatorCategory { get; set; } 
        public string? IndicatorPlannedDesc { get; set; } 
        public string? IndicatorRealizedDesc { get; set; }
        public string? IndicatorResultDesc { get; set; }
        public double IndicatorKminDesc { get; set; }
        public string? DataSourceText { get; set; }
        public string? DataCalculationText { get; set; }
        public string? EvaluationTypeText { get; set; }

       // public List<StageDto> Stages { get; set; }
    }
}
