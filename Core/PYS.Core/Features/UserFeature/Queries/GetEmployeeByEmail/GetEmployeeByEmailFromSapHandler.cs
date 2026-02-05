using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Helpers;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail
{
    public class GetEmployeeByEmailFromSapHandler(
        IUnitOfWork<ISAPDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetEmployeeByEmailFromSapHandler> logger) :
        BaseHandler<ISAPDbContext, GetEmployeeByEmailFromSapHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetEmployeeByEmailFromSapRequest, GetEmployeeByEmailFromSapResponse>
    {
        public async Task<GetEmployeeByEmailFromSapResponse> Handle(
            GetEmployeeByEmailFromSapRequest request,
            CancellationToken cancellationToken)
        {
            var sapEmployee = await UnitOfWork.GetAppReadRepository<SapEmployeeList>()
                .GetAsync(e => e.Mail == EmailNormalizer.Normalize(request.Email));

            return Mapper.Map<GetEmployeeByEmailFromSapResponse>(sapEmployee);
        }
    }
}