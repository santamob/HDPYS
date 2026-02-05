using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetEmployeeByStarDate
{
    public class GetEmployeeByStartDateRequest : IRequest<List<GetEmployeeByStartDateResponse>>
    {
        public DateOnly StartDate { get; set; }
    }
}
