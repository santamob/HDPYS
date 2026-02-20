using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetActivePeriods
{
    /// <summary>
    /// Kullanıcının atanmış olduğu aktif dönemleri getiren handler.
    /// PeriodInUser tablosu üzerinden SAP çalışan eşleşmesi yapılır.
    /// </summary>
    public class GetActivePeriodsHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        UserManager<AppUser> userManager,
        IMapper mapper,
        ILogger<GetActivePeriodsHandler> logger
    ) : BaseHandler<IAppDbContext, GetActivePeriodsHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetActivePeriodsRequest, List<ActivePeriodDto>>
    {
        public async Task<List<ActivePeriodDto>> Handle(GetActivePeriodsRequest request, CancellationToken cancellationToken)
        {
            var appUser = await userManager.FindByIdAsync(userContextService.UserId.ToString());
            if (appUser == null)
            {
                return new List<ActivePeriodDto>();
            }

            var userEmail = appUser.Email!.ToUpperInvariant();

            // Kullanıcının PeriodInUser kayıtları üzerinden atanmış dönem ID'lerini al
            var periodInUsers = await unitOfWork.GetAppReadRepository<PeriodInUser>()
                .GetAllAsync(p => p.Mail.ToUpper() == userEmail && p.IsActive);
            var periodIds = periodInUsers.Select(p => p.PeriodId).Distinct().ToList();

            if (!periodIds.Any())
            {
                return new List<ActivePeriodDto>();
            }

            // Sadece kullanıcının atanmış olduğu aktif dönemleri getir
            var periods = await unitOfWork.GetAppReadRepository<Periods>()
                .GetAllAsync(p => p.IsActive && periodIds.Contains(p.Id));

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
