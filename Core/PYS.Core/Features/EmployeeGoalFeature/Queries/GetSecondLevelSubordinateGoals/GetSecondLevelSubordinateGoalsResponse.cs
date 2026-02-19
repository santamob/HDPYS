using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetSecondLevelSubordinateGoals
{
    /// <summary>
    /// 2. derece astların hedefleri yanıtı
    /// </summary>
    public class GetSecondLevelSubordinateGoalsResponse
    {
        public List<SubordinateGoalDto> Subordinates { get; set; } = new();
        public int PendingApprovalCount { get; set; }
    }
}
