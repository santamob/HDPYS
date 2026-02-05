using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetUserRolesById
{
    public class GetUserRolesByIdHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetUserRolesByIdHandler> logger) : BaseHandler<IAppDbContext, GetUserRolesByIdHandler>(unitOfWork, mapper, logger), IRequestHandler<GetUserRolesByIdRequest, List<Guid>>
    {
        public async Task<List<Guid>> Handle(GetUserRolesByIdRequest request, CancellationToken cancellationToken)
        {
            var roles = await unitOfWork.GetAppReadRepository<AppUserRole>()
            .GetAllAsync(x => x.UserId == request.Id);

            return roles.Select(x => x.RoleId).ToList();
        }
    }
}
