using MediatR;
using PYS.Core.Application.Common.Constants;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;

namespace PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory
{
    public class GetAllCategoryQueryRequest : IRequest<IList<GetAllCategoryQueryResponse>>, ICacheableQuery
    {
        public string CacheKey => CacheKeys.GetAllCategories;

        public double? CacheTime => 60;
    }
}
