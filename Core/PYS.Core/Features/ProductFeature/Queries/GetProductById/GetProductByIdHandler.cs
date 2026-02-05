using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.ProductFeature.Queries.GetProductById
{
    public class GetProductByIdHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetProductByIdHandler> logger) : BaseHandler<IAppDbContext, GetProductByIdHandler>(unitOfWork, mapper, logger), IRequestHandler<GetProductByIdRequest, GetProductByIdResponse>
    {
        public async Task<GetProductByIdResponse> Handle(GetProductByIdRequest request, CancellationToken cancellationToken)
        {
            var product = await unitOfWork.GetAppReadRepository<Product>().GetAsync(p => p.Id == request.Id);
            var response = mapper.Map<GetProductByIdResponse>(product);
            return response;
        }
    }
}
