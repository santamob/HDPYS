using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorsByFormType
{
    public class GetIndicatorsByFormTypeResponse
    {
        public Guid FormTypeId { get; set; }
        public Guid Id { get; set; }
        public string IndicatorName { get; set; }
    }
}
