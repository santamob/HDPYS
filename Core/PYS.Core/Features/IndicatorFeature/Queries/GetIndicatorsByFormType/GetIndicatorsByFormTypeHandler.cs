using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using System.Collections.Generic;
using System.Threading;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorsByFormType
{
    public class GetIndicatorsByFormTypeHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetIndicatorsByFormTypeHandler> logger
    )
        : BaseHandler<IAppDbContext, GetIndicatorsByFormTypeHandler>(unitOfWork, mapper, logger),
          IRequestHandler<GetIndicatorsByFormTypeRequest, List<GetIndicatorsByFormTypeResponse>>
    {
        public async Task<List<GetIndicatorsByFormTypeResponse>> Handle(GetIndicatorsByFormTypeRequest request, CancellationToken cancellationToken)
        {
            var indicators = await unitOfWork
                .GetAppReadRepository<Indicator>()
                .GetAllAsync(x => x.FormTypeId == request.FormTypeId && x.IsActive);

            return mapper.Map<List<GetIndicatorsByFormTypeResponse>>(indicators);
        }
    }
}
