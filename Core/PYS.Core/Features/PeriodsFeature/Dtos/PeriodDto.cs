using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Dtos;

namespace PYS.Core.Application.Features.PeriodsFeature.Dtos
{
    public class PeriodDto
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; }
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public bool IsActive { get; set; }
        public List<FormTypesDto> FormTypes { get; set; } = new();
        public List<LocationsDto> Locations { get; set; } = new();
    }
}
