using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;

namespace PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes
{
    public class GetAllFormTypesQueryHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllFormTypesQueryHandler> logger) : BaseHandler<IAppDbContext, GetAllFormTypesQueryHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllFormTypesQueryRequest, IList<GetAllFormTypesQueryResponse>>
    {

        public async Task<IList<GetAllFormTypesQueryResponse>> Handle(GetAllFormTypesQueryRequest request, CancellationToken cancellationToken)
        {
            var formTypes = await unitOfWork.GetAppReadRepository<FormTypes>().GetAllAsync(c => c.IsActive);

            return mapper.Map<List<GetAllFormTypesQueryResponse>>(formTypes);
        }
    }
}
