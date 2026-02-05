using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Commands.Create
{
    public class CreatePeriodInUserHandler(
      IUnitOfWork<IAppDbContext> unitOfWork,
       IUserContextService userContextService,
      IMapper mapper,
      ILogger<CreatePeriodInUserHandler> logger
  ) : BaseHandler<IAppDbContext, CreatePeriodInUserHandler>(unitOfWork, mapper, logger),
      IRequestHandler<CreatePeriodInUserRequest, CreatePeriodInUserResponse>
    {
        public async Task<CreatePeriodInUserResponse> Handle(CreatePeriodInUserRequest request, CancellationToken cancellationToken)
        {
            try
            {
                var entities = request.Users.Select(user => new PeriodInUser
                {
                    PeriodId = request.PeriodId,
                    PeriodText = request.PeriodText,
                    PerNr = user.PerNr,
                    Persk=user.Persk,
                    Ename = user.Ename,
                    MPernr = user.MPernr,
                    Persg =user.Persg,
                    MMail = user.MMail,
                    OID = user.OID,
                    SID = user.SID,
                    Orgtx = user.Orgtx,
                    PLSTX = user.PLSTX,
                    Mail = user.Mail,
                    AccAsgmnt = user.AccAsgmnt,
                    AccAsgmntT = user.AccAsgmntT,
                    Level = user.Level,
                    CreatedIp = userContextService.IpAddress,
                    AppUserCreatedId = userContextService.UserId
                }).ToList();

                await unitOfWork.GetAppWriteRepository<PeriodInUser>().AddRangeAsync(entities);
                await unitOfWork.SaveAsync();

                return new CreatePeriodInUserResponse
                {
                    IsSuccess = true,
                    Message = $"{entities.Count} kullanıcı başarıyla eklendi."
                };
            }
            catch (Exception ex)
            {
                return new CreatePeriodInUserResponse
                {
                    IsSuccess = false,
                    Message = $"Hata oluştu: {ex.Message}"
                };
            }
        }
    }
}
