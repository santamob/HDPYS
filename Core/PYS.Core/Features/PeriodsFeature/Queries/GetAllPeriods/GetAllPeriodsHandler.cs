using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods
{
    public class GetAllPeriodsHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllPeriodsHandler> logger) : BaseHandler<IAppDbContext, GetAllPeriodsHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllPeriodsRequest, IList<GetAllPeriodsResponse>>
    {
        public async Task<IList<GetAllPeriodsResponse>> Handle(GetAllPeriodsRequest request, CancellationToken cancellationToken)
        {
            var periods = await unitOfWork.GetAppReadRepository<Periods>().GetAllAsync(include: x => x.Include(p => p.FormTypesPeriods)
            .Include(p => p.LocationPeriods), orderBy: q => q.OrderByDescending(p => p.CreatedDate));

            return mapper.Map<List<GetAllPeriodsResponse>>(periods);
        }
    }

}
