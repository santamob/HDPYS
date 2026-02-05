using PYS.Core.Application.Features.FormsFeature.Dtos;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;

namespace PYS.Web.ViewModels.AllForms
{
    public class AllFormsViewModel
    {
        public List<FormTypesDto> FormTypes { get; set; }
        public List<IndicatorDto> Indicator { get; set; }

        public List<FormDto> Forms { get; set; }
    }
}
