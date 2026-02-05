using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory
{
    public class GetAllCategoryQueryHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllCategoryQueryHandler> logger) : BaseHandler<IAppDbContext, GetAllCategoryQueryHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllCategoryQueryRequest, IList<GetAllCategoryQueryResponse>>
    {
        public async Task<IList<GetAllCategoryQueryResponse>> Handle(GetAllCategoryQueryRequest request, CancellationToken cancellationToken)
        {
            var categories = await unitOfWork.GetAppReadRepository<Category>().GetAllAsync(c => c.IsActive);

            return mapper.Map<List<GetAllCategoryQueryResponse>>(categories);
        }
    }
}
