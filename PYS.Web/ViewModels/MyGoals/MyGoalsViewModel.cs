using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Domain.Enums;

namespace PYS.Web.ViewModels.MyGoals
{
    /// <summary>
    /// Hedeflerim ana sayfa ViewModel'i - Dashboard ve liste
    /// </summary>
    public class MyGoalsIndexViewModel
    {
        public List<EmployeeGoalDto> Goals { get; set; } = new();
        public List<ActivePeriodDto> Periods { get; set; } = new();
        public Guid? SelectedPeriodId { get; set; }
        public int? SelectedStatus { get; set; }

        // Dashboard istatistikleri
        public int TotalCount { get; set; }
        public int DraftCount { get; set; }
        public int PendingFirstApprovalCount { get; set; }
        public int PendingSecondApprovalCount { get; set; }
        public int ApprovedCount { get; set; }
        public int RejectedCount { get; set; }
        public decimal TotalWeight { get; set; }
    }

    /// <summary>
    /// Hedef oluşturma ViewModel'i
    /// </summary>
    public class CreateGoalViewModel
    {
        public Guid PeriodId { get; set; }
        public List<ActivePeriodDto> Periods { get; set; } = new();
        public List<AvailableIndicatorDto> Indicators { get; set; } = new();
        public List<Guid> SelectedIndicatorIds { get; set; } = new();
        public List<GoalItemDto> Goals { get; set; } = new();
        public decimal CurrentTotalWeight { get; set; }
    }

    /// <summary>
    /// Hedef düzenleme ViewModel'i
    /// </summary>
    public class EditGoalViewModel
    {
        public Guid Id { get; set; }
        public Guid PeriodId { get; set; }
        public string PeriodName { get; set; } = string.Empty;
        public Guid? IndicatorId { get; set; }
        public string? IndicatorName { get; set; }
        public string GoalTitle { get; set; } = string.Empty;
        public string? GoalDescription { get; set; }
        public decimal TargetValue { get; set; }
        public string? TargetUnit { get; set; }
        public decimal Weight { get; set; }
        public DateTime? StartDate { get; set; }
        public DateTime? EndDate { get; set; }
        public string? EmployeeNote { get; set; }
        public GoalStatus Status { get; set; }
        public decimal CurrentTotalWeight { get; set; }
    }

    /// <summary>
    /// Önizleme ViewModel'i
    /// </summary>
    public class PreviewGoalsViewModel
    {
        public Guid PeriodId { get; set; }
        public string PeriodName { get; set; } = string.Empty;
        public List<EmployeeGoalDto> Goals { get; set; } = new();
        public decimal TotalWeight { get; set; }
        public bool CanSubmit => TotalWeight == 100;
    }
}
