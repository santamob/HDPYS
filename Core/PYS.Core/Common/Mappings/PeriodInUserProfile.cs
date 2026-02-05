using AutoMapper;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using PYS.Core.Domain.Entities;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Common.Mappings
{
    public class PeriodInUserProfile : Profile
    {
        public PeriodInUserProfile()
        {
            CreateMap<PeriodInUserDto, PeriodInUser>()
                .ForMember(dest => dest.Id, opt => opt.Ignore());
        }
    }
}
