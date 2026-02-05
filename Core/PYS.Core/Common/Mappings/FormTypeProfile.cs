
using AutoMapper;
using PYS.Core.Application.Features.FormTypesFeature.Commands.Create;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
   public class FormTypeProfile:Profile
    {
        public FormTypeProfile() 
        {
            CreateMap<GetAllFormTypesQueryResponse, FormTypesDto>().ReverseMap();
            CreateMap<GetAllFormTypesQueryResponse, FormTypes>().ReverseMap();
            CreateMap<CreateFormTypeCommandRequest, CreateFormTypeDto>().ReverseMap();
            CreateMap<CreateFormTypeCommandRequest, FormTypes>().ReverseMap();
            CreateMap<FormTypes, FormTypes>().ReverseMap();
            CreateMap<GetAllFormTypesQueryResponse, FormTypesDto>();
        }
    }
}
