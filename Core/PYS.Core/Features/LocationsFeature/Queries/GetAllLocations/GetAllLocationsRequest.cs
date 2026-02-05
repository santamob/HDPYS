using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;

namespace PYS.Core.Application.Features.LocationsFeature.Queries.GetAllLocations
{
    public class GetAllLocationsRequest : IRequest<IList<GetAllLocationsResponse>>
    {
    }
}
