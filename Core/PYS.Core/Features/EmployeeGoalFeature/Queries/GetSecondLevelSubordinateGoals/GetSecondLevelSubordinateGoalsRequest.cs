using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSecondLevelSubordinateGoals
{
    /// <summary>
    /// 2. derece astların hedeflerini getirme isteği.
    /// Mevcut kullanıcının astlarının astlarının hedeflerini döner.
    /// Sadece PendingSecondApproval, Approved, Rejected durumundaki hedefler gösterilir.
    /// </summary>
    public class GetSecondLevelSubordinateGoalsRequest : IRequest<GetSecondLevelSubordinateGoalsResponse>
    {
        /// <summary>
        /// Dönem filtresi (opsiyonel)
        /// </summary>
        public Guid? PeriodId { get; set; }
    }
}
