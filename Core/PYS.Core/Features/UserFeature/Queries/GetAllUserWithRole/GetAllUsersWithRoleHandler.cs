using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole
{
    public class GetAllUsersWithRoleHandler(
        IMediator mediator,
        UserManager<AppUser> userManager,
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        IHttpContextAccessor httpContextAccessor,
        ILogger<GetAllUsersWithRoleHandler> logger) :
        BaseHandler<IAppDbContext, GetAllUsersWithRoleHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetAllUsersWithRoleRequest, IList<GetAllUsersWithRoleResponse>>
    {
        public async Task<IList<GetAllUsersWithRoleResponse>> Handle(
        GetAllUsersWithRoleRequest request,
            CancellationToken cancellationToken)
        {
            var users = await UnitOfWork.GetAppReadRepository<AppUser>().GetAllAsync();
            var map = Mapper.Map<List<GetAllUsersWithRoleResponse>>(users);

            foreach (var user in map)
            {
                var sapEmployee = await mediator.Send(
                    new GetEmployeeByEmailFromSapRequest { Email = user.Email },
                    cancellationToken);

                if (sapEmployee != null)
                {
                    // Aktif Personel
                    user.FirstName = sapEmployee.Vorna ?? "";
                    user.LastName = sapEmployee.Nachn ?? "";
                    user.Position = sapEmployee.Plstx ?? "";
                    user.Unit = sapEmployee.Orgtx ?? "";
                    user.IdentificationNumber = sapEmployee.Merni ?? "";
                    user.LockoutEnabled = false;
                }
                else
                {
                    // Pasif Personel
                    var sapEmployeeLog = await mediator.Send(
                        new GetEmployeeLogByEmailFromSapRequest { Email = user.Email },
                        cancellationToken);

                    if (sapEmployeeLog != null)
                    {
                        user.FirstName = sapEmployeeLog.Vorna ?? "";
                        user.LastName = sapEmployeeLog.Nachn ?? "";
                        user.Position = sapEmployeeLog.Plstx ?? "";
                        user.Unit = sapEmployeeLog.Orgtx ?? "";
                        user.IdentificationNumber = sapEmployeeLog.Merni ?? "";
                        user.LockoutEnabled = true;
                        user.IsActive = false;
                    }
                }

                var findUser = await userManager.FindByIdAsync(user.Id.ToString());
            }

            return map;
        }
    }
}