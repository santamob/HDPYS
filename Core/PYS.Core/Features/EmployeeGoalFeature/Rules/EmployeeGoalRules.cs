using PYS.Core.Application.Interfaces.Localization;
using PYS.Core.Domain.Entities;
using PYS.Core.Domain.Enums;
using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Rules
{
    /// <summary>
    /// Çalışan hedef girişi iş kuralları.
    /// Ağırlık kontrolü, tekrar kontrolü, düzenleme/silme yetki kontrolleri burada yapılır.
    /// </summary>
    public class EmployeeGoalRules(ILanguageService languageService) : BaseRules
    {
        /// <summary>
        /// Toplam ağırlık 100'ü geçemez
        /// </summary>
        public Task<RuleResult> TotalWeightShouldNotExceed100(decimal currentTotalWeight, decimal newWeight)
        {
            if (currentTotalWeight + newWeight > 100)
                return Task.FromResult(Failure("Toplam ağırlık 100'ü geçemez. Mevcut toplam: " + currentTotalWeight + ", eklenmek istenen: " + newWeight));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Güncelleme sırasında toplam ağırlık kontrolü (mevcut hedefin ağırlığı çıkarılarak hesaplanır)
        /// </summary>
        public Task<RuleResult> TotalWeightShouldNotExceed100OnUpdate(decimal totalWeightExcludingCurrent, decimal newWeight)
        {
            if (totalWeightExcludingCurrent + newWeight > 100)
                return Task.FromResult(Failure("Toplam ağırlık 100'ü geçemez. Diğer hedeflerin toplamı: " + totalWeightExcludingCurrent + ", yeni ağırlık: " + newWeight));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Aynı dönem + aynı gösterge için tekrar hedef oluşturulamaz
        /// </summary>
        public Task<RuleResult> GoalShouldNotBeDuplicate(EmployeeGoal? existingGoal)
        {
            if (existingGoal is not null)
                return Task.FromResult(Failure("Bu dönem için aynı gösterge ile zaten bir hedef oluşturulmuş."));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Hedef sadece Draft veya Rejected durumundayken düzenlenebilir
        /// </summary>
        public Task<RuleResult> GoalShouldBeEditableStatus(GoalStatus status)
        {
            if (status != GoalStatus.Draft && status != GoalStatus.Rejected)
                return Task.FromResult(Failure("Bu hedef yalnızca Taslak veya Reddedildi durumundayken düzenlenebilir."));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Hedef sadece Draft durumundayken silinebilir
        /// </summary>
        public Task<RuleResult> GoalShouldBeDraftForDeletion(GoalStatus status)
        {
            if (status != GoalStatus.Draft)
                return Task.FromResult(Failure("Yalnızca Taslak durumundaki hedefler silinebilir."));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Hedef onaya gönderilebilmesi için Draft veya Rejected durumda olmalı
        /// </summary>
        public Task<RuleResult> GoalsShouldBeSubmittable(IEnumerable<GoalStatus> statuses)
        {
            if (statuses.Any(s => s != GoalStatus.Draft && s != GoalStatus.Rejected))
                return Task.FromResult(Failure("Yalnızca Taslak veya Reddedildi durumundaki hedefler onaya gönderilebilir."));
            return Task.FromResult(Success());
        }

        /// <summary>
        /// Hedefin mevcut olup olmadığını kontrol eder
        /// </summary>
        public Task<RuleResult> GoalShouldExist(EmployeeGoal? goal)
        {
            if (goal is null)
                return Task.FromResult(Failure("Hedef bulunamadı."));
            return Task.FromResult(Success());
        }
    }
}
