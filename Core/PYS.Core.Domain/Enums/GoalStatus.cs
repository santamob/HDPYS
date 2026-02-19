using System.ComponentModel.DataAnnotations;

namespace PYS.Core.Domain.Enums
{
    /// <summary>
    /// Çalışan hedeflerinin durum bilgisini temsil eder.
    /// İki kademeli onay akışı: Draft -> PendingFirstApproval -> PendingSecondApproval -> Approved
    /// </summary>
    public enum GoalStatus
    {
        /// <summary>
        /// Taslak - Henüz onaya gönderilmemiş
        /// </summary>
        [Display(Name = "Taslak")]
        Draft = 0,

        /// <summary>
        /// 1. Yönetici Onayında - Doğrudan yönetici onayına gönderilmiş
        /// </summary>
        [Display(Name = "1. Yönetici Onayında")]
        PendingFirstApproval = 1,

        /// <summary>
        /// Onaylandı - Tüm onay süreçleri tamamlanmış
        /// </summary>
        [Display(Name = "Onaylandı")]
        Approved = 2,

        /// <summary>
        /// Reddedildi - Yönetici tarafından reddedilmiş
        /// </summary>
        [Display(Name = "Reddedildi")]
        Rejected = 3,

        /// <summary>
        /// 2. Üst Yönetici Onayında - 1. yönetici onayladı, üst yönetici onayı bekleniyor
        /// </summary>
        [Display(Name = "2. Üst Yönetici Onayında")]
        PendingSecondApproval = 4
    }
}
