using PYS.Core.Domain.Enums;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Dtos
{
    /// <summary>
    /// Çalışan hedef listesi için DTO
    /// </summary>
    public class EmployeeGoalDto
    {
        public Guid Id { get; set; }
        public Guid PeriodId { get; set; }
        public string PeriodName { get; set; } = string.Empty;
        public Guid? IndicatorId { get; set; }
        public string? IndicatorName { get; set; }
        public Guid EmployeeId { get; set; }
        public string GoalTitle { get; set; } = string.Empty;
        public string? GoalDescription { get; set; }
        public decimal TargetValue { get; set; }
        public string? TargetUnit { get; set; }
        public decimal Weight { get; set; }
        public DateTime? StartDate { get; set; }
        public DateTime? EndDate { get; set; }
        public decimal? ActualValue { get; set; }
        public decimal? AchievementRate { get; set; }
        public decimal? CalculatedScore { get; set; }
        public decimal? WeightedScore { get; set; }
        public GoalStatus Status { get; set; }
        public string StatusText { get; set; } = string.Empty;
        public DateTime? ApprovalDate { get; set; }
        public string? ManagerNote { get; set; }
        public string? EmployeeNote { get; set; }
        public int RejectionCount { get; set; }
        public bool IsActive { get; set; }
    }

    /// <summary>
    /// Hedef oluşturma için DTO
    /// </summary>
    public class CreateEmployeeGoalDto
    {
        public Guid PeriodId { get; set; }
        public List<GoalItemDto> Goals { get; set; } = new();
    }

    /// <summary>
    /// Tek bir hedef öğesi (gösterge bazlı veya manuel)
    /// </summary>
    public class GoalItemDto
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

    /// <summary>
    /// Hedef güncelleme için DTO
    /// </summary>
    public class UpdateEmployeeGoalDto
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

    /// <summary>
    /// Mevcut gösterge seçimi için DTO
    /// </summary>
    public class AvailableIndicatorDto
    {
        public Guid Id { get; set; }
        public string IndicatorName { get; set; } = string.Empty;
        public string? IndicatorDesc { get; set; }
        public string? FormTypeText { get; set; }
        public string? IndicatorCategory { get; set; }
    }

    /// <summary>
    /// Aktif dönem listesi için DTO
    /// </summary>
    public class ActivePeriodDto
    {
        public Guid Id { get; set; }
        public int Year { get; set; }
        public string Term { get; set; } = string.Empty;
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public string DisplayName => $"{Year} - {Term}";
    }
}
