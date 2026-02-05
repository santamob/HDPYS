using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct
{
    public class GetAllProductQueryHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllProductQueryHandler> logger) : BaseHandler<IAppDbContext, GetAllProductQueryHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllProductQueryRequest, IList< GetAllProductQueryResponse>>
    {
        public async Task<IList<GetAllProductQueryResponse>> Handle(GetAllProductQueryRequest request, CancellationToken cancellationToken)
        {
            var products = await unitOfWork.GetAppReadRepository<Product>().GetAllAsync(include: x => x.Include(p => p.Category));

            var response = mapper.Map<IList<GetAllProductQueryResponse>>(products);

            return response;
        }
    }
}
