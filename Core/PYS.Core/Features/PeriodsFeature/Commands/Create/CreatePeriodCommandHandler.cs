using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PeriodsFeature.Commands.Create
{
    public class CreatePeriodCommandHandler(IUserContextService userContextService,
    IUnitOfWork<IAppDbContext> unitOfWork,
    IMapper mapper,
    ILogger<CreatePeriodCommandHandler> logger)
    : BaseHandler<IAppDbContext, CreatePeriodCommandHandler>(unitOfWork, mapper, logger),
      IRequestHandler<CreatePeriodCommandRequest, CreatePeriodCommandResponse>
    {
        public async Task<CreatePeriodCommandResponse> Handle(CreatePeriodCommandRequest request, CancellationToken cancellationToken)
        {


            var formTypes = await unitOfWork.GetAppReadRepository<FormTypes>().GetAllAsync(x => request.FormTypeIds.Contains(x.Id));
            var locations = await unitOfWork.GetAppReadRepository<Location>().GetAllAsync(x => request.LocationIds.Contains(x.Id));

           //var a = request.FormTypeIds.Select(id => new FormTypes { Id = id }).ToList();

            var period = new Periods
            {
                Year = request.Year,
                Term = request.Term,
                StartDate = request.StartDate,
                EndDate = request.EndDate,
                HasStaging = request.HasStaging,
                //FormTypes = formTypes.ToList(),
                //Locations = locations.ToList(),
                CreatedIp = userContextService.IpAddress,
                AppUserCreatedId = userContextService.UserId
            };

            //period.FormTypes = formTypes;
            //period.Locations = locations;

            await unitOfWork.GetAppWriteRepository<Periods>().AddAsync(period);
            await unitOfWork.SaveAsync();


        
            foreach (var formType in formTypes)
            {
                var formTypePeriod = new FormTypesPeriods
                {
                    PeriodsId = period.Id,
                    FormTypesId = formType.Id
                };
                await unitOfWork.GetAppWriteRepository<FormTypesPeriods>().AddAsync(formTypePeriod);
            }

          
            foreach (var location in locations)
            {
                var locationPeriod = new LocationPeriods
                {
                    PeriodsId = period.Id,
                    LocationsId = location.Id
                };
                await unitOfWork.GetAppWriteRepository<LocationPeriods>().AddAsync(locationPeriod);
            }

         
            await unitOfWork.SaveAsync();

            return new CreatePeriodCommandResponse
            {
                IsSuccess = true,
                Message = "Dönem başarıyla eklendi."
            };
        }
    }
    

}
