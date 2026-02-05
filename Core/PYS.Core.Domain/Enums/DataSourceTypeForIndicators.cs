using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Enums
{
    public enum DataSourceTypeForIndicators
    {
        [Display(Name = "Otomatik")]
        Auto = 1,

        [Display(Name = "Manuel")]
        Manual = 2,

        [Display(Name = "Veri Yok")]
        NoData = 3,

        [Display(Name = "Manuel Sonuç")]
        ManualResult = 4
    }
}
