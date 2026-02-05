using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetPeriodInUser
{
    public class GetPeriodInUserHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetPeriodInUserHandler> logger) 
        : BaseHandler<IAppDbContext, GetPeriodInUserHandler>(unitOfWork, mapper, logger), IRequestHandler<GetPeriodInUserRequest, IList<PeriodInUserDto>>
    {
        public async Task<IList<PeriodInUserDto>> Handle(GetPeriodInUserRequest request, CancellationToken cancellationToken)
        {
            var allPeriodUsers = await unitOfWork.GetAppReadRepository<PeriodInUser>()
           .GetAllAsync(x => x.PeriodId == request.PeriodId);

            var dtoList = allPeriodUsers.Select(user => new PeriodInUserDto
            {
                Id = user.Id,
                PerNr = user.PerNr,
                Ename = user.Ename,
                PLSTX = user.PLSTX,
                OID = user.OID,
                SID = user.SID,
                Orgtx = user.Orgtx,
                Mail = user.Mail,
                AccAsgmnt = user.AccAsgmnt,
                AccAsgmntT=user.AccAsgmntT,
                Level=user.Level,
                MMail=user.MMail,
                Persg=user.Persg,
                Persk=user.Persk,
                MPernr=user.MPernr,
                MFullName = allPeriodUsers.FirstOrDefault(m => m.PerNr == user.MPernr)?.Ename
            }).ToList();

            return dtoList;
        }
     }
}
