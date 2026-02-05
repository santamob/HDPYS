using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Dtos;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Create;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Update;
using PYS.Core.Application.Features.PeriodsFeature.Dtos;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class PeriodProfile : Profile
    {
        public PeriodProfile()
        {
            CreateMap<CreatePeriodDto, CreatePeriodCommandRequest>();
            CreateMap<UpdatePeriodDto, UpdatePeriodCommandRequest>();

            CreateMap<Periods, GetAllPeriodsResponse>();


            CreateMap<GetAllPeriodsResponse, PeriodDto>();

            CreateMap<Periods, PeriodDto>()
                .ForMember(dest => dest.FormTypes,
                    opt => opt.MapFrom(src => src.FormTypesPeriods.Select(ft => new FormTypesDto
                    {
                        Id = ft.FormTypesId
                    })))
                .ForMember(dest => dest.Locations,
                    opt => opt.MapFrom(src => src.LocationPeriods.Select(loc => new LocationsDto
                    {
                        Id = loc.LocationsId
                       
                    })));

            CreateMap<Periods, GetAllPeriodsForPotantialEvaluationResponse>().ReverseMap();

        }
    }
}
