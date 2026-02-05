using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail
{
    public class GetEmployeeLogByEmailFromSapHandler :
        BaseHandler<ISAPDbContext, GetEmployeeLogByEmailFromSapHandler>,
        IRequestHandler<GetEmployeeLogByEmailFromSapRequest, GetEmployeeLogByEmailFromSapResponse>
    {
        public GetEmployeeLogByEmailFromSapHandler(
            IUnitOfWork<ISAPDbContext> unitOfWork,
            IMapper mapper,
            ILogger<GetEmployeeLogByEmailFromSapHandler> logger)
            : base(unitOfWork, mapper, logger)
        {
        }

        public async Task<GetEmployeeLogByEmailFromSapResponse> Handle(
            GetEmployeeLogByEmailFromSapRequest request,
            CancellationToken cancellationToken)
        {
            var sapEmployee = await UnitOfWork.GetAppReadRepository<SapEmployeeListLog>()
                .GetAsync(e => e.Mail == request.Email);

            if (sapEmployee == null)
            {
                throw new KeyNotFoundException($"Email adresi {request.Email} ile çalışan log kaydı bulunamadı");
            }

            return Mapper.Map<GetEmployeeLogByEmailFromSapResponse>(sapEmployee);
        }
    }
}