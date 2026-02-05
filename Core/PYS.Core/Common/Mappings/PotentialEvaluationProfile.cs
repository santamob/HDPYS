using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllEvaluationPeriods;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class PotentialEvaluationProfile : Profile
    {
        public PotentialEvaluationProfile()
        {
            CreateMap<PotentialEvaluationDto, PotentialEvaluation>();
            CreateMap<PotentialEvaluationDetailDto, PotentialEvaluationDetail>();

            CreateMap<GetAllEvaluationPeriodResponse, PotentialEvaluationPeriodDto>().ReverseMap();
            CreateMap<GetAllPeriodsForPotantialEvaluationResponse, PeriodEvaluationDto>().ReverseMap();

            CreateMap<PotentialEvaluation, GetPotentialFormsByIdResponse>()
                .ForMember(dest => dest.CriteriaDetails, opt => opt.MapFrom(src => src.PotentialEvaluationDetails));
            CreateMap<PotentialEvaluationDetail, PotentialCriteriaDetailDto>();


        }
    }
}
