using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetPeriodById
{
    public class GetPeriodByIdHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetPeriodByIdHandler> logger)
        : BaseHandler<IAppDbContext, GetPeriodByIdHandler>(unitOfWork, mapper, logger),
          IRequestHandler<GetPeriodByIdRequest, GetPeriodByIdResponse>
    {
        public async Task<GetPeriodByIdResponse> Handle(GetPeriodByIdRequest request, CancellationToken cancellationToken)
        {
            var period = await unitOfWork.GetAppReadRepository<Periods>().GetAsync(
                x => x.Id == request.Id,
                include: x => x.Include(p => p.FormTypesPeriods).Include(p => p.LocationPeriods)  
            );

            if (period == null)
                throw new Exception("Dönem bulunamadı");

            return new GetPeriodByIdResponse
            {
                Id = period.Id,
                Year = period.Year,
                Term = period.Term,
                StartDate = period.StartDate,
                EndDate = period.EndDate,
                HasStaging = period.HasStaging,
                FormTypeIds = period.FormTypesPeriods.Select(f => f.FormTypesId).ToList(),
                LocationIds = period.LocationPeriods.Select(l => l.LocationsId).ToList()
            };
        }
    }
}

