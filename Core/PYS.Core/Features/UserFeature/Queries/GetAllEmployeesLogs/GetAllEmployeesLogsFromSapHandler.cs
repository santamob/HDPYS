using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeesLogs
{
    public class GetAllEmployeesLogsFromSapHandler(
        IUnitOfWork<ISAPDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetAllEmployeesLogsFromSapHandler> logger) :
        BaseHandler<ISAPDbContext, GetAllEmployeesLogsFromSapHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetAllEmployeesLogsFromSapRequest, IList<GetAllEmployeesLogsFromSapResponse>>
    {
        public async Task<IList<GetAllEmployeesLogsFromSapResponse>> Handle(
            GetAllEmployeesLogsFromSapRequest request,
            CancellationToken cancellationToken)
        {
            var sapEmployees = await UnitOfWork.GetAppReadRepository<SapEmployeeListLog>().GetAllAsync();
            return Mapper.Map<List<GetAllEmployeesLogsFromSapResponse>>(sapEmployees);
        }
    }
}