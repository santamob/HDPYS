using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk
{
    public class GetAllEmployeeWithPerSkHandler(
        IUnitOfWork<ISAPDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetAllEmployeeWithPerSkHandler> logger,
        UserManager<AppUser> userManager,
        IMediator mediator) : BaseHandler<ISAPDbContext, GetAllEmployeeWithPerSkHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllEmployeeWithPerSkRequest, GetAllEmployeeWithPerSkResponse>
    {

        public async Task<GetAllEmployeeWithPerSkResponse> Handle(GetAllEmployeeWithPerSkRequest request, CancellationToken cancellationToken)
        {         
            var employee = await UnitOfWork.GetAppReadRepository<SapEmployeeList>().GetAsync(x => x.Pernr == request.PerSk);
            if (employee is null)
                return null;

            string managerName = string.Empty;

            if (employee.MPernr.HasValue)
            {
                var manager = await UnitOfWork.GetAppReadRepository<SapEmployeeList>()
                    .GetAsync(x => x.Pernr == employee.MPernr.Value);

                if (manager is not null)
                    managerName = $"{manager.Ename}";
            }

            var response = Mapper.Map<GetAllEmployeeWithPerSkResponse>(employee);
            response.ManagerName = managerName;

            return response;

        }
    }
}
