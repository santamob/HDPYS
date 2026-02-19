using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSubordinateGoals
{
    /// <summary>
    /// 1. derece astların hedeflerini getirme isteği.
    /// Mevcut kullanıcının doğrudan astlarının hedeflerini döner.
    /// </summary>
    public class GetSubordinateGoalsRequest : IRequest<GetSubordinateGoalsResponse>
    {
        /// <summary>
        /// Dönem filtresi (opsiyonel)
        /// </summary>
        public Guid? PeriodId { get; set; }
    }
}
