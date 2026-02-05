using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using PYS.Core.Application.Features.FormsFeature.Dtos;
using PYS.Core.Application.Features.FormsFeature.Queries.GetAllForms;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{

    public class FormProfile : Profile
{
    public FormProfile()
    {
        CreateMap<Forms, GetAllFormsResponse>();
        CreateMap<GetAllFormsResponse, FormDto>();

        CreateMap<FormDetails, FormDetailDto>()
            .ForMember(dest => dest.IndicatorName, opt => opt.MapFrom(src => src.Indicator.IndicatorName));

        CreateMap<Forms, FormDto>()
            .ForMember(dest => dest.FormDetails, opt => opt.MapFrom(src => src.FormDetails));
        }
}


}
