using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal
{
    /// <summary>
    /// Hedef güncelleme komutu.
    /// Sadece Draft veya Rejected durumundaki hedefler güncellenebilir.
    /// </summary>
    public class UpdateGoalCommandRequest : IRequest<UpdateGoalCommandResponse>
    {
        public Guid Id { get; set; }
        public string GoalTitle { get; set; } = string.Empty;
        public string? GoalDescription { get; set; }
        public decimal TargetValue { get; set; }
        public string? TargetUnit { get; set; }
        public decimal Weight { get; set; }
        public DateTime? StartDate { get; set; }
        public DateTime? EndDate { get; set; }
        public string? EmployeeNote { get; set; }
    }
}
