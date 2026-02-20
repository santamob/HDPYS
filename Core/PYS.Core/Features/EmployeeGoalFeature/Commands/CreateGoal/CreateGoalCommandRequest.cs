using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal
{
    /// <summary>
    /// Yeni hedef oluşturma komutu.
    /// Çalışan, seçtiği dönem için birden fazla hedef ekleyebilir.
    /// </summary>
    public class CreateGoalCommandRequest : IRequest<CreateGoalCommandResponse>
    {
        /// <summary>
        /// Hedefin ait olduğu dönem
        /// </summary>
        public Guid PeriodId { get; set; }

        /// <summary>
        /// Oluşturulacak hedef listesi
        /// </summary>
        public List<CreateGoalItem> Goals { get; set; } = new();
    }

    /// <summary>
    /// Tek bir hedef öğesi
    /// </summary>
    public class CreateGoalItem
    {
        public Guid? IndicatorId { get; set; }
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
