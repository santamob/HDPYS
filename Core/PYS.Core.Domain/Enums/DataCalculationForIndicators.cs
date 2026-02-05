using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Enums
{
    public enum DataCalculationForIndicators
    {
        [Display(Name = "Oto")]
        Oto = 1,

        [Display(Name = "Realizasyon")]
        Realizasyon = 2,

        [Display(Name = "Sapma(MD)")]
        Sapma = 3,

        [Display(Name = "Veri Yok")]
        NoData = 4,

       [Display(Name = "Manuel Sonuç")]
        ManuelResult = 5

    }
}
