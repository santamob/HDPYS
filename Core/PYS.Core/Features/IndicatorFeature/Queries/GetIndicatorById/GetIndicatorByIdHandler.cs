using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetPeriodById;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById
{
    public class GetIndicatorByIdHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetIndicatorByIdHandler> logger)
        : BaseHandler<IAppDbContext, GetIndicatorByIdHandler>(unitOfWork, mapper, logger),
          IRequestHandler<GetIndicatorByIdRequest, GetIndicatorByIdResponse>
    {

        public async Task<GetIndicatorByIdResponse>Handle(GetIndicatorByIdRequest request, CancellationToken cancellation)
        {
            var indicator = await unitOfWork.GetAppReadRepository<Indicator>().GetAsync(
                x => x.Id == request.Id,
                include: x => x.Include(P => P.Stages)
                );

            if (indicator == null)
                throw new Exception("Gösterge bulunamadu");

            return mapper.Map<GetIndicatorByIdResponse>(indicator);
        }

    }
    
}
