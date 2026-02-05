using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using PYS.Core.Application.Features.LocationsFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Queries.GetAllLocations;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class LocationProfile : Profile
    {
        public LocationProfile()
        {
            CreateMap<Location, GetAllLocationsResponse>();
            CreateMap<GetAllLocationsResponse, LocationsDto>();
        }
    }
}
