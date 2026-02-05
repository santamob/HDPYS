using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.PeriodsFeature.Queries.GetEmployeeByStarDate
{
    public class GetEmployeeByStartDateHandler(
        IUnitOfWork<ISAPDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetEmployeeByStartDateHandler> logger)
        : BaseHandler<ISAPDbContext, GetEmployeeByStartDateHandler>(unitOfWork, mapper, logger),
         IRequestHandler<GetEmployeeByStartDateRequest, List<GetEmployeeByStartDateResponse>>
    {
        public async Task<List<GetEmployeeByStartDateResponse>> Handle(GetEmployeeByStartDateRequest request, CancellationToken cancellationToken)
        {
            var employees = await UnitOfWork
                    .GetAppReadRepository<SapEmployeeList>()
                    .GetAllAsync(x => x.Isbas <= request.StartDate && x.EndDa == new DateOnly(9999, 12, 31));

            var sapEmployeesDtos = Mapper.Map<List<GetEmployeeByStartDateResponse>>(employees);


            return sapEmployeesDtos;

           
        }
    }
}
