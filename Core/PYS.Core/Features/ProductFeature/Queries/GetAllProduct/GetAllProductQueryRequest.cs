using MediatR;
using PYS.Core.Application.Common.Constants;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;

namespace PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct
{
    public class GetAllProductQueryRequest : IRequest<IList<GetAllProductQueryResponse>>, ICacheableQuery
    {
        public string CacheKey => CacheKeys.GetAllProducts;

        public double? CacheTime => 0;
    }
}
