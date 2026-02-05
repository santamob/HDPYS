using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Dtos;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods
{
    public class GetAllPeriodsResponse
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; }
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public bool HasStaging { get; set; }

        // FormTypes ve Locations 
        public List<FormTypesDto> FormTypes { get; set; }
        public List<LocationsDto> Locations { get; set; }

        public bool IsActive { get; set; }
    }
}
