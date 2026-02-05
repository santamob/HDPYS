using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Constants;
using PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetUserInPeriodById;
using PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Commands.Update
{
    public class UpdateUserInPeriodHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<UpdateUserInPeriodHandler> logger)
        : BaseHandler<IAppDbContext, UpdateUserInPeriodHandler>(unitOfWork, mapper, logger),
        IRequestHandler<UpdateUserInPeriodRequest, UpdateUserInPeriodResponse>
    {
        public async Task<UpdateUserInPeriodResponse> Handle(UpdateUserInPeriodRequest request, CancellationToken cancellationToken)
        {
            var existingUser = await unitOfWork.GetAppReadRepository<PeriodInUser>().GetAsync(p => p.Id == request.Id);

            if (existingUser is not null)
            {
                existingUser.SID = request.SID;
                existingUser.PLSTX = request.PLSTX;
                existingUser.OID = request.OID;
                existingUser.Orgtx = request.Orgtx;
                existingUser.MMail = request.MMail;
                existingUser.MPernr = request.MPernr;
                existingUser.Level = request.Level;
            }

            await unitOfWork.GetAppWriteRepository<PeriodInUser>().UpdateAsync(existingUser);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {
               
                return new UpdateUserInPeriodResponse
                {
                    IsSuccess = true,
                    Message = "Kullanıcı başarıyla güncellendi."
                };
            }
            else
                return  new UpdateUserInPeriodResponse
                {
                    IsSuccess = false,
                    Message = "Kullanıcı bulunamadı."
                };
        }
    }
}
