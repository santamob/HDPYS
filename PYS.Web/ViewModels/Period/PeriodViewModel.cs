using System;
using System.Collections.Generic;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.PeriodsFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Dtos;
using PYS.Core.Domain.Entities; 

namespace PYS.Web.ViewModels.Period
{
    public class PeriodViewModel
    {
      
        public List<PeriodDto> Periods { get; set; }
        public List<FormTypesDto> FormTypes { get; set; }
        public List<LocationsDto> Locations { get; set; }

    }
}