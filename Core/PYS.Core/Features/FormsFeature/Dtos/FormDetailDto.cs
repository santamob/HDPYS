using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.FormsFeature.Dtos
{
    public class FormDetailDto
    {
        public Guid IndicatorId { get; set; }
        public int Weight { get; set; }
        public string IndicatorName { get; set; }
    }
}
