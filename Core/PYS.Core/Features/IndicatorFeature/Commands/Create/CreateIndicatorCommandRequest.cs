using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using PYS.Core.Domain.Enums;

namespace PYS.Core.Application.Features.IndicatorFeature.Commands.Create
{
    public class CreateIndicatorCommandRequest : IRequest<CreateIndicatorCommandResponse>
    {
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }

        public string IndicatorName { get; set; }
        public string IndicatorPeriod { get; set; }
        public string IndicatorPeriodText { get; set; }
        public string IndicatorDesc { get; set; }
        public string IndicatorDetailDesc { get; set; }
        public string? IndicatorCategory { get; set; } // sadece Yetkinlik formu için
        public string? IndicatorPlannedDesc { get; set; } // sadece Hedef formu için
        public string? IndicatorRealizedDesc { get; set; }
        public string? IndicatorResultDesc { get; set; }
        public double IndicatorKminDesc { get; set; }

        public double IndicatorKmaxDesc { get; set; }

        public DataSourceTypeForIndicators IndicatorDataSource { get; set; } // enum
        public DataCalculationForIndicators IndicatorDataCalculation { get; set; } // enum
        public EvaluationTypeForIndicators IndicatorEvaluationType { get; set; } // enum

        public List<StageDto> Stages { get; set; }
    }
}
