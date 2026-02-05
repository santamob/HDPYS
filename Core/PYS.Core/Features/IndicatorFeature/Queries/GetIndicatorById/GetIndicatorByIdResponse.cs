using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using PYS.Core.Domain.Enums;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById
{
    public class GetIndicatorByIdResponse
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

        public int DataSource { get; set; }
        public string? DataSourceText { get; set; }

        public int DataCalculation { get; set; }
        public string? DataCalculationText { get; set; }
        public int EvaluationType { get; set; }
        public string? EvaluationTypeText { get; set; }

        public List<StageDto> Stages { get; set; }
    }
}
