using PYS.Core.Application.Features.PeriodsFeature.Dtos;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos;

namespace PYS.Web.ViewModels.PotentialEvaluations
{
    public class PotentialEvaluationViewModel
    {
        public List<PotentialEvaluationPeriodDto> PotentialEvaluationPeriods { get; set; }
        public List<PeriodEvaluationDto> Periods { get; set; }
    }
}
