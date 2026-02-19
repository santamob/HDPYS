using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSubordinateGoals
{
    /// <summary>
    /// 1. derece astların hedefleri yanıtı
    /// </summary>
    public class GetSubordinateGoalsResponse
    {
        public List<SubordinateGoalDto> Subordinates { get; set; } = new();
        public int PendingApprovalCount { get; set; }
    }
}
