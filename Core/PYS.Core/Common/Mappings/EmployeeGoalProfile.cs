using AutoMapper;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal;
using PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    /// <summary>
    /// EmployeeGoal AutoMapper profili.
    /// DTO, Command ve Entity arasındaki dönüşümleri tanımlar.
    /// </summary>
    public class EmployeeGoalProfile : Profile
    {
        public EmployeeGoalProfile()
        {
            // Entity -> DTO
            CreateMap<EmployeeGoal, EmployeeGoalDto>()
                .ForMember(dest => dest.PeriodName,
                    opt => opt.MapFrom(src => src.Period != null ? $"{src.Period.Year} - {src.Period.Term}" : ""))
                .ForMember(dest => dest.IndicatorName,
                    opt => opt.MapFrom(src => src.Indicator != null ? src.Indicator.IndicatorName : null));

            // DTO -> Command
            CreateMap<GoalItemDto, CreateGoalItem>().ReverseMap();
            CreateMap<CreateEmployeeGoalDto, CreateGoalCommandRequest>().ReverseMap();
            CreateMap<UpdateEmployeeGoalDto, UpdateGoalCommandRequest>().ReverseMap();

            // Indicator -> AvailableIndicatorDto
            CreateMap<Indicator, AvailableIndicatorDto>();

            // Period -> ActivePeriodDto
            CreateMap<Periods, ActivePeriodDto>();
        }
    }
}
