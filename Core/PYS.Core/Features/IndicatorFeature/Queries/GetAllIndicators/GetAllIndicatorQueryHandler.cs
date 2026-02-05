using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Metadata.Ecma335;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators
{
    public class GetAllIndicatorQueryHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllIndicatorQueryHandler> logger) : BaseHandler<IAppDbContext, GetAllIndicatorQueryHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllIndicatorQueryRequest, IList<GetAllIndicatorQueryResponse>>
    {
        public async Task<IList<GetAllIndicatorQueryResponse>> Handle(GetAllIndicatorQueryRequest request, CancellationToken cancellationToken)
        {
            // var indicators = await unitOfWork.GetAppReadRepository<Indicator>().GetAllAsync(include: x => x.Include(p => p.Stages));

            var indicators = await unitOfWork.GetAppReadRepository<Indicator>().GetAllAsync();

            return mapper.Map<List<GetAllIndicatorQueryResponse>>(indicators);
        }

    }
}
