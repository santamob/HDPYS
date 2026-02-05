using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.FormsFeature.Dtos;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.FormsFeature.Queries.GetAllForms
{
    public class GetAllFormsResponse
    {
        public Guid Id { get; set; }
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }
        public string FormName { get; set; }
        public int TotalWeight { get; set; }

        public List<IndicatorDto> Indicators { get; set; }
        public List<FormDetailDto> FormDetails { get; set; }
    }
}
