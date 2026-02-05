using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodInUserFeature.Commands.Update;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Create;
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

namespace PYS.Core.Application.Features.PeriodsFeature.Commands.Update
{
    public class UpdatePeriodCommandHandler
    (IUserContextService userContextService,
    IUnitOfWork<IAppDbContext> unitOfWork,
    IMapper mapper,
    ILogger<UpdatePeriodCommandHandler> logger)
    : BaseHandler<IAppDbContext, UpdatePeriodCommandHandler>(unitOfWork, mapper, logger),
      IRequestHandler<UpdatePeriodCommandRequest, UpdatePeriodCommandResponse>
    {
        public async Task<UpdatePeriodCommandResponse> Handle(UpdatePeriodCommandRequest request, CancellationToken cancellationToken)
        {


            var period = await unitOfWork.GetAppReadRepository<Periods>().GetAsync(p => p.Id == request.Id,
                                                                                   include: q => q.Include(p => p.FormTypesPeriods)
                                                                                                   .Include(p => p.LocationPeriods));
           
            if (period == null) 
                return new UpdatePeriodCommandResponse { IsSuccess = false, Message = "Periyot bulunamadı." };

            period.Year = request.Year;
            period.Term = request.Term;
            period.HasStaging = request.HasStaging;
            period.ModifiedDate = DateTime.Now;
            period.ModifiedIp = userContextService.IpAddress;
            period.AppUserModifiedId = userContextService.UserId;

            // Form tiplerini güncelle
            period.FormTypesPeriods.Clear();
            foreach (var formType in request.FormTypeIds)
            {
                period.FormTypesPeriods.Add(new FormTypesPeriods
                {
                    PeriodsId = period.Id,
                    FormTypesId = formType
                });
            }

            //// Lokasyonları güncelle
            period.LocationPeriods.Clear();
            foreach (var location in request.LocationIds)
            {
                period.LocationPeriods.Add(new LocationPeriods
                {
                    PeriodsId = period.Id,
                    LocationsId = location
                });
            }

            await unitOfWork.GetAppWriteRepository<Periods>().UpdateAsync(period);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {

                return new UpdatePeriodCommandResponse
                {
                    IsSuccess = true,
                    Message = "Dönem başarıyla güncellendi."
                };
            }
            else
                return new UpdatePeriodCommandResponse
                {
                    IsSuccess = false,
                    Message = "Hata!!."
                };
        }
    }
}
