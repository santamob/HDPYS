
using MediatR;
using PYS.Core.Application.Common.Constants;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;

namespace PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes
{
    public class GetAllFormTypesQueryRequest : IRequest<IList<GetAllFormTypesQueryResponse>>, ICacheableQuery
    {
         public string CacheKey => CacheKeys.GetAllFormTypes;

        public double? CacheTime => 60;
    }
}
