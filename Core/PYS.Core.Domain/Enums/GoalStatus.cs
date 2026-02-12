using System.ComponentModel.DataAnnotations;

namespace PYS.Core.Domain.Enums
{
    /// <summary>
    /// Çalışan hedeflerinin durum bilgisini temsil eder.
    /// </summary>
    public enum GoalStatus
    {
        /// <summary>
        /// Taslak - Henüz onaya gönderilmemiş
        /// </summary>
        [Display(Name = "Taslak")]
        Draft = 0,

        /// <summary>
        /// Onay Bekliyor - Yönetici onayına gönderilmiş
        /// </summary>
        [Display(Name = "Onay Bekliyor")]
        PendingApproval = 1,

        /// <summary>
        /// Onaylandı - Yönetici tarafından onaylanmış
        /// </summary>
        [Display(Name = "Onaylandı")]
        Approved = 2,

        /// <summary>
        /// Reddedildi - Yönetici tarafından reddedilmiş
        /// </summary>
        [Display(Name = "Reddedildi")]
        Rejected = 3
    }
}
