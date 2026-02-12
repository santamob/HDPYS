using MediatR;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetAvailableIndicators
{
    /// <summary>
    /// Çalışanın seçebileceği gösterge havuzunu getirir.
    /// Mevcut Indicators tablosundan aktif göstergeleri çeker.
    /// </summary>
    public class GetAvailableIndicatorsRequest : IRequest<List<AvailableIndicatorDto>>
    {
        /// <summary>
        /// Dönem ID (opsiyonel - dönem bazlı filtreleme için)
        /// </summary>
        public Guid? PeriodId { get; set; }
    }
}
