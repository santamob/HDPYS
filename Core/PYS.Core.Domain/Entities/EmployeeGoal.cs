using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    /// <summary>
    /// Çalışan hedef girişi entity'si.
    /// Çalışanların performans hedeflerini oluşturup yönetici onayına gönderdiği kayıtları temsil eder.
    /// PeriodInUser tablosu üzerinden SAP çalışan verisiyle ilişkilendirilir.
    /// </summary>
    public class EmployeeGoal : EntityBase
    {
        /// <summary>
        /// Hedefin ait olduğu dönem (PeriodInUser üzerinden de erişilebilir, doğrudan filtreleme kolaylığı için tutulur)
        /// </summary>
        public Guid PeriodId { get; set; }

        /// <summary>
        /// PeriodInUser tablosundaki kayıt - Çalışanın dönem atamasını temsil eder.
        /// SAP PerNr, yönetici bilgisi (MPernr), organizasyon bilgisi buradan gelir.
        /// </summary>
        public Guid PeriodInUserId { get; set; }

        /// <summary>
        /// Seçilen gösterge (Indicators tablosundan)
        /// </summary>
        public Guid? IndicatorId { get; set; }

        /// <summary>
        /// Onaylayan yöneticinin ID'si (AppUser)
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
        /// Hedef durumu (Draft, PendingFirstApproval, PendingSecondApproval, Approved, Rejected)
        /// </summary>
        public GoalStatus Status { get; set; } = GoalStatus.Draft;

        /// <summary>
        /// Onay tarihi (geriye dönük uyumluluk - 1. yönetici onay tarihi olarak da kullanılır)
        /// </summary>
        public DateTime? ApprovalDate { get; set; }

        /// <summary>
        /// 1. Yönetici onay tarihi
        /// </summary>
        public DateTime? FirstApprovalDate { get; set; }

        /// <summary>
        /// 2. Üst yönetici onay tarihi
        /// </summary>
        public DateTime? SecondApprovalDate { get; set; }

        /// <summary>
        /// 2. üst yöneticiyi onaylayan kişinin ID'si (AppUser)
        /// </summary>
        public Guid? ApprovedBySecondManagerId { get; set; }

        /// <summary>
        /// Yönetici notu (max 2000 karakter)
        /// </summary>
        public string? ManagerNote { get; set; }

        /// <summary>
        /// 2. Üst yönetici notu (max 2000 karakter)
        /// </summary>
        public string? SecondManagerNote { get; set; }

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
        /// İlişkili PeriodInUser kaydı (çalışan-dönem ilişkisi, SAP verileri)
        /// </summary>
        public PeriodInUser PeriodInUser { get; set; } = null!;

        /// <summary>
        /// İlişkili gösterge
        /// </summary>
        public Indicator? Indicator { get; set; }
    }
}
