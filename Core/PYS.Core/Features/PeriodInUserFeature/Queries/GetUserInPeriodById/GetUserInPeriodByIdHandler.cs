using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetPeriodInUser;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetUserInPeriodById
{
    public class GetUserInPeriodByIdHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetUserInPeriodByIdHandler> logger)
        : BaseHandler<IAppDbContext, GetUserInPeriodByIdHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetUserInPeriodByIdRequest, GetUserInPeriodByIdResponse>
    {
        public async Task<GetUserInPeriodByIdResponse> Handle(GetUserInPeriodByIdRequest request, CancellationToken cancellationToken)
        {
            var user = await unitOfWork.GetAppReadRepository<PeriodInUser>()
            .GetAsync(x => x.Id == request.Id);

            if (user == null)
                return null;

            return new GetUserInPeriodByIdResponse
            {
                Id=user.Id,
                PerNr = user.PerNr,
                Ename = user.Ename,
                SID = user.SID,
                PLSTX = user.PLSTX,
                MPernr = user.MPernr,
                MMail = user.MMail,
                Level = user.Level,
                OID = user.OID,
                Orgtx = user.Orgtx
                // diğer alanlar gelcek...
            };
        }
    }
}
