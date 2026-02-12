using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    /// <summary>
    /// Çalışan hedef girişi entity'si.
    /// Çalışanların performans hedeflerini oluşturup yönetici onayına gönderdiği kayıtları temsil eder.
    /// </summary>
    public class EmployeeGoal : EntityBase
    {
        /// <summary>
        /// Hedefin ait olduğu dönem
        /// </summary>
        public Guid PeriodId { get; set; }

        /// <summary>
        /// Seçilen gösterge (Indicators tablosundan)
        /// </summary>
        public Guid? IndicatorId { get; set; }

        /// <summary>
        /// Hedefi oluşturan çalışanın ID'si (AppUser)
        /// </summary>
        public Guid EmployeeId { get; set; }

        /// <summary>
        /// Onaylayan yöneticinin ID'si (AppUsers.M_Pernr kolonundan SAP yönetici)
        /// </summary>
        public Guid? ApprovedByManagerId { get; set; }

        /// <summary>
        /// Hedef başlığı (max 500 karakter)
        /// </summary>
        public string GoalTitle { get; set; } = string.Empty;

        /// <summary>
        /// Hedef açıklaması (max 4000 karakter)
        /// </summary>
        public string? GoalDescription { get; set; }

        /// <summary>
        /// Hedef değeri
        /// </summary>
        public decimal TargetValue { get; set; }

        /// <summary>
        /// Hedef birimi (%, adet, TL vb.) (max 50 karakter)
        /// </summary>
        public string? TargetUnit { get; set; }

        /// <summary>
        /// Ağırlık (1-100 arası, precision 5,2)
        /// </summary>
        public decimal Weight { get; set; }

        /// <summary>
        /// Hedef başlangıç tarihi
        /// </summary>
        public DateTime? StartDate { get; set; }

        /// <summary>
        /// Hedef bitiş tarihi
        /// </summary>
        public DateTime? EndDate { get; set; }

        /// <summary>
        /// Gerçekleşen değer
        /// </summary>
        public decimal? ActualValue { get; set; }

        /// <summary>
        /// Gerçekleşme oranı (%)
        /// </summary>
        public decimal? AchievementRate { get; set; }

        /// <summary>
        /// Hesaplanan puan
        /// </summary>
        public decimal? CalculatedScore { get; set; }

        /// <summary>
        /// Ağırlıklı puan
        /// </summary>
        public decimal? WeightedScore { get; set; }

        /// <summary>
        /// Hedef durumu (Draft, PendingApproval, Approved, Rejected)
        /// </summary>
        public GoalStatus Status { get; set; } = GoalStatus.Draft;

        /// <summary>
        /// Onay tarihi
        /// </summary>
        public DateTime? ApprovalDate { get; set; }

        /// <summary>
        /// Yönetici notu (max 2000 karakter)
        /// </summary>
        public string? ManagerNote { get; set; }

        /// <summary>
        /// Çalışan notu (max 2000 karakter)
        /// </summary>
        public string? EmployeeNote { get; set; }

        /// <summary>
        /// Ret sayısı
        /// </summary>
        public int RejectionCount { get; set; }

        // Navigation Properties

        /// <summary>
        /// İlişkili dönem
        /// </summary>
        public Periods Period { get; set; } = null!;

        /// <summary>
        /// İlişkili gösterge
        /// </summary>
        public Indicator? Indicator { get; set; }
    }
}
