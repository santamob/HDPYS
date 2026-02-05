using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorsByFormType;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class IndicatorProfile : Profile
    {
        public IndicatorProfile() 
        {

            // Stage -> StageDto
            CreateMap<Stage, StageDto>();

            // Indicator -> IndicatorDto (içinde Stage listesiyle birlikte)
            CreateMap<Indicator, IndicatorDto>()
                .ForMember(dest => dest.Stages, opt => opt.MapFrom(src => src.Stages));

            CreateMap<Indicator, GetAllIndicatorQueryResponse>();
            CreateMap<GetAllIndicatorQueryResponse, IndicatorDto>();
            CreateMap<Indicator, GetIndicatorByIdResponse>();

            CreateMap<Indicator, GetIndicatorsByFormTypeResponse>();
        }
    }
}
