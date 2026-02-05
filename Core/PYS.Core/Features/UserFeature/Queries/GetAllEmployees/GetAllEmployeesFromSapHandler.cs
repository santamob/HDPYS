using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Dtos;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees
{
    public class GetAllEmployeesFromSapHandler :
     BaseHandler<ISAPDbContext, GetAllEmployeesFromSapHandler>,
     IRequestHandler<GetAllEmployeesFromSapRequest, IList<GetAllEmployeesFromSapResponse>>
    {
        public GetAllEmployeesFromSapHandler(
            IUnitOfWork<ISAPDbContext> unitOfWork,
            IMapper mapper,
            ILogger<GetAllEmployeesFromSapHandler> logger)
            : base(unitOfWork, mapper, logger)
        {
        }

        public async Task<IList<GetAllEmployeesFromSapResponse>> Handle(
            GetAllEmployeesFromSapRequest request,
            CancellationToken cancellationToken)
        {
            var sapEmployees = await UnitOfWork.GetAppReadRepository<SapEmployeeList>().GetAllAsync();
            var sapEmployeesDtos = Mapper.Map<List<GetAllEmployeesFromSapResponse>>(sapEmployees);

            var userDict = sapEmployeesDtos.ToDictionary(x => x.Pernr);

            foreach (var user in sapEmployeesDtos)
            {
                if (user.MPernr.HasValue && userDict.TryGetValue(user.MPernr.Value, out var manager))
                {
                    user.ManagerName = manager.Ename;
                }
                else
                {
                    user.ManagerName = "-";
                }
            }

            return sapEmployeesDtos;
        }
    }
}
