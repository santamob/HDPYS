using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Indicator : EntityBase
    {

        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }
        public string IndicatorName { get; set; }
        public string IndicatorPeriod { get; set; } // 1ay, 3ay, 6ay vs.
        public string IndicatorPeriodText { get; set; }
        public string IndicatorDesc { get; set; }
        public string IndicatorDetailDesc { get; set; }
        public string? IndicatorCategory { get; set; } // sadece yetkinlik formu
        public string? IndicatorPlannedDesc { get; set; } // sadece hedef formu
        public string? IndicatorRealizedDesc { get; set; } // sadece hedef formu
        public string? IndicatorResultDesc { get; set; } // sadece hedef formu

        public DataSourceTypeForIndicators DataSource { get; set; }
        public string DataSourceText { get; set; }
        public DataCalculationForIndicators DataCalculation { get; set; }
        public string DataCalculationText { get; set; }

        public EvaluationTypeForIndicators EvaluationType { get; set; }

        public string EvaluationTypeText { get; set; }

        public double IndicatorKminDesc { get; set; }

        public double IndicatorKmaxDesc { get; set; }

        public FormTypes FormType { get; set; } // Navigation
        public ICollection<Stage> Stages { get; set; }
    }

}
