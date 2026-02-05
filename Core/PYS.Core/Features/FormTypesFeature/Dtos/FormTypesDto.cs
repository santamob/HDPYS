using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.FormTypesFeature.Dtos
{
    public class FormTypesDto
    {
        public Guid Id { get; set; }
        public string FormTypeName { get; set; }
        public bool IsActive { get; set; }
    }
}
