using AutoMapper;
using PYS.Core.Application.Features.UserFeature.Commands.CreateUser;
using PYS.Core.Application.Features.UserFeature.Commands.UpdateUser;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetEmployeeByStarDate;

namespace PYS.Core.Application.Common.Mappings
{
    public class UserProfile : Profile
    {
        public UserProfile()
        {
            CreateMap<UserDto, AppUser>().ReverseMap();
            CreateMap<UserCreateDto, AppUser>().ReverseMap();
            CreateMap<GetEmployeeByEmailFromSapResponse, SapEmployeeList>().ReverseMap();
            CreateMap<GetEmployeeLogByEmailFromSapResponse, SapEmployeeListLog>().ReverseMap();
            CreateMap<AppUser, GetAllUsersWithRoleResponse>().ReverseMap();
            CreateMap<UserDto, GetAllUsersWithRoleResponse>().ReverseMap();
            CreateMap<SapEmployeeList, GetAllEmployeesFromSapResponse>().ReverseMap();
            CreateMap<CreateUserCommandRequest, UserCreateDto>().ReverseMap();
            CreateMap<UpdateUserCommandRequest, UserUpdateDto>().ReverseMap();
            CreateMap<GetEmployeeByUserIdResponse, UserUpdateDto>().ReverseMap();
            CreateMap<GetEmployeeByUserIdResponse, UserDto>().ReverseMap();
            CreateMap<CreateUserCommandRequest, AppUser>().ReverseMap();
            CreateMap<GetAllEmployeesFromSapResponse, SAPUserDto>();
            CreateMap<GetEmployeeByUserIdResponse, SAPUserDto>();
            CreateMap<SapEmployeeList, GetAllEmployeeWithPerSkResponse>();
            CreateMap<SapEmployeeList, OrgTreeNode>()
                .ForMember(dest => dest.FullName, opt => opt.MapFrom(src => $"{src.Vorna} {src.Nachn}"))
                .ForMember(dest => dest.Title, opt => opt.MapFrom(src => src.Plstx))
                .ForMember(dest => dest.Department, opt => opt.MapFrom(src => src.Orgtx))
                .ForMember(dest => dest.PerNr, opt => opt.MapFrom(src => src.Pernr))
                .ForMember(dest => dest.MPernr, opt => opt.MapFrom(src => src.MPernr));

            CreateMap<SapEmployeeList, GetEmployeeByStartDateResponse>().ReverseMap();
            CreateMap<GetEmployeeByStartDateResponse, SAPUserDto>();
        }
    }
}
