using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetMyGoals
{
    /// <summary>
    /// Çalışanın kendi hedeflerini listeler.
    /// Opsiyonel dönem ve durum filtresi destekler.
    /// </summary>
    public class GetMyGoalsQueryRequest : IRequest<GetMyGoalsQueryResponse>
    {
        /// <summary>
        /// Dönem filtresi (opsiyonel)
        /// </summary>
        public Guid? PeriodId { get; set; }

        /// <summary>
        /// Durum filtresi (opsiyonel)
        /// </summary>
        public int? Status { get; set; }
    }
}
