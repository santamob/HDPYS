using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetMyGoals
{
    /// <summary>
    /// Çalışan hedef listesi yanıtı - dashboard istatistikleri ile birlikte
    /// </summary>
    public class GetMyGoalsQueryResponse
    {
        public List<EmployeeGoalDto> Goals { get; set; } = new();
        public int TotalCount { get; set; }
        public int DraftCount { get; set; }
        public int PendingFirstApprovalCount { get; set; }
        public int PendingSecondApprovalCount { get; set; }
        public int ApprovedCount { get; set; }
        public int RejectedCount { get; set; }
        public decimal TotalWeight { get; set; }
    }
}
