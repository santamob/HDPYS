using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.FormsFeature.Dtos
{
    public class FormDto
    {
        public Guid Id { get; set; }
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }
        public string FormName { get; set; }
        public int TotalWeight { get; set; }

        public string IndicatorName { get; set; }
        public ICollection<FormDetailDto> FormDetails { get; set; }
    }
}
