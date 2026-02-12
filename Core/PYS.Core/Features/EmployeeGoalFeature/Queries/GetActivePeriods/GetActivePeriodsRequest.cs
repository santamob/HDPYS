using MediatR;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetActivePeriods
{
    /// <summary>
    /// Aktif dönemleri getirir.
    /// Çalışanın hedef girebileceği dönemleri listeler.
    /// </summary>
    public class GetActivePeriodsRequest : IRequest<List<ActivePeriodDto>>
    {
    }
}
