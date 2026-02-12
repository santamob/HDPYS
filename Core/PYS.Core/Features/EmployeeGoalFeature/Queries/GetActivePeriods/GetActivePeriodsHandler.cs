using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetActivePeriods
{
    /// <summary>
    /// Aktif dönemleri getiren handler.
    /// IsActive olan ve tarih aralığı uygun dönemleri döner.
    /// </summary>
    public class GetActivePeriodsHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetActivePeriodsHandler> logger
    ) : BaseHandler<IAppDbContext, GetActivePeriodsHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetActivePeriodsRequest, List<ActivePeriodDto>>
    {
        public async Task<List<ActivePeriodDto>> Handle(GetActivePeriodsRequest request, CancellationToken cancellationToken)
        {
            var periods = await unitOfWork.GetAppReadRepository<Periods>()
                .GetAllAsync(p => p.IsActive);

            return periods.Select(p => new ActivePeriodDto
            {
                Id = p.Id,
                Year = p.Year,
                Term = p.Term,
                StartDate = p.StartDate,
                EndDate = p.EndDate
            }).OrderByDescending(p => p.Year).ThenBy(p => p.Term).ToList();
        }
    }
}
