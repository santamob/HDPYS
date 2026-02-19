using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Web.ViewModels.Evaluations
{
    /// <summary>
    /// Değerlendirme Formları ana sayfa ViewModel'i - 3 sekmeli görünüm
    /// </summary>
    public class EvaluationsIndexViewModel
    {
        // Tab 1: Kendime Ait
        public List<EmployeeGoalDto> MyGoals { get; set; } = new();
        public List<ActivePeriodDto> Periods { get; set; } = new();
        public Guid? SelectedPeriodId { get; set; }
        public int MyDraftCount { get; set; }
        public int MyPendingFirstCount { get; set; }
        public int MyPendingSecondCount { get; set; }
        public int MyApprovedCount { get; set; }
        public int MyRejectedCount { get; set; }
        public decimal MyTotalWeight { get; set; }

        // Tab 2: Astlarıma Ait
        public List<SubordinateGoalDto> SubordinateGoals { get; set; } = new();
        public int SubordinatePendingCount { get; set; }

        // Tab 3: Astımın Astlarına Ait
        public List<SubordinateGoalDto> SecondLevelSubordinateGoals { get; set; } = new();
        public int SecondLevelPendingCount { get; set; }

        public string ActiveTab { get; set; } = "own";
    }

    /// <summary>
    /// Onay/Red işlemi ViewModel'i
    /// </summary>
    public class ApproveRejectViewModel
    {
        public Guid PeriodInUserId { get; set; }
        public Guid PeriodId { get; set; }
        public string? ManagerNote { get; set; }
        /// <summary>
        /// 1 = 1. Yönetici, 2 = 2. Üst Yönetici
        /// </summary>
        public int ApprovalLevel { get; set; }
    }
}
