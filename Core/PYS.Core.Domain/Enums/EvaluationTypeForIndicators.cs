using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Enums
{
    public enum EvaluationTypeForIndicators
    {
        [Display(Name = "Oto")]
        Auto = 1,

        [Display(Name = "Objektif")]
        Objektif = 2,

        [Display(Name = "Subjektif")]
        Subjektif = 3
    }
}
