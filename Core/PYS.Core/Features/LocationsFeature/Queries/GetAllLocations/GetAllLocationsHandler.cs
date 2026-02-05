using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.LocationsFeature.Queries.GetAllLocations
{
    public class GetAllLocationsHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllLocationsHandler> logger) : BaseHandler<IAppDbContext, GetAllLocationsHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllLocationsRequest, IList<GetAllLocationsResponse>>
    {
        public async Task<IList<GetAllLocationsResponse>> Handle(GetAllLocationsRequest request, CancellationToken cancellationToken)
        {
            var locations = await unitOfWork.GetAppReadRepository<Location>().GetAllAsync();

            return mapper.Map<List<GetAllLocationsResponse>>(locations);
        }
    }
}
