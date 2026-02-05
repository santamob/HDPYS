using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Enums
{
    public enum PeriodRangeCompetencePotentialInterviewForIndicators
    {
        [Display(Name = "6 Ay")]
        SixMonth = 6,
        [Display(Name = "Yıllık")]
        Annual = 99,
    }
}
