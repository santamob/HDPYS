using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Enums
{
    public enum PeriodRangeTargetForIndicators
    {
        [Display(Name = "1 Ay")]
        OneMonth = 1,
        [Display(Name = "2 Ay")]
        TwoMonth = 2,
        [Display(Name = "3 Ay")]
        ThereeMonth = 3,
        [Display(Name = "4 Ay")]
        FourMonth = 4,
        [Display(Name = "5 Ay")]
        FiveMonth = 5,
        [Display(Name = "6 Ay")]
        SixMonth = 6,
        [Display(Name = "Yıllık")]
        Annual = 99,

    }
}
