using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using PYS.Core.Domain.Entities;

namespace PYS.Infrastructure.Persistence.Configurations
{
    /// <summary>
    /// EmployeeGoal entity'si için EF Core yapılandırması.
    /// Tablo adı, kolon kısıtlamaları ve ilişkiler burada tanımlanır.
    /// </summary>
    public class EmployeeGoalConfiguration : IEntityTypeConfiguration<EmployeeGoal>
    {
        public void Configure(EntityTypeBuilder<EmployeeGoal> builder)
        {
            // Tablo adı
            builder.ToTable("EmployeeGoals");

            // GoalTitle - Zorunlu, max 500 karakter
            builder.Property(x => x.GoalTitle)
                .IsRequired()
                .HasMaxLength(500);

            // GoalDescription - Opsiyonel, max 4000 karakter
            builder.Property(x => x.GoalDescription)
                .HasMaxLength(4000);

            // TargetValue - Precision 18,2
            builder.Property(x => x.TargetValue)
                .HasPrecision(18, 2);

            // TargetUnit - Opsiyonel, max 50 karakter
            builder.Property(x => x.TargetUnit)
                .HasMaxLength(50);

            // Weight - Precision 5,2 (1-100 arası)
            builder.Property(x => x.Weight)
                .HasPrecision(5, 2);

            // ActualValue - Precision 18,2
            builder.Property(x => x.ActualValue)
                .HasPrecision(18, 2);

            // AchievementRate - Precision 18,2
            builder.Property(x => x.AchievementRate)
                .HasPrecision(18, 2);

            // CalculatedScore - Precision 18,2
            builder.Property(x => x.CalculatedScore)
                .HasPrecision(18, 2);

            // WeightedScore - Precision 18,2
            builder.Property(x => x.WeightedScore)
                .HasPrecision(18, 2);

            // ManagerNote - Opsiyonel, max 2000 karakter
            builder.Property(x => x.ManagerNote)
                .HasMaxLength(2000);

            // SecondManagerNote - Opsiyonel, max 2000 karakter
            builder.Property(x => x.SecondManagerNote)
                .HasMaxLength(2000);

            // EmployeeNote - Opsiyonel, max 2000 karakter
            builder.Property(x => x.EmployeeNote)
                .HasMaxLength(2000);

            // Status - Enum olarak int saklanır
            builder.Property(x => x.Status)
                .HasConversion<int>();

            // İlişkiler
            builder.HasOne(x => x.Period)
                .WithMany()
                .HasForeignKey(x => x.PeriodId)
                .OnDelete(DeleteBehavior.Restrict);

            builder.HasOne(x => x.PeriodInUser)
                .WithMany()
                .HasForeignKey(x => x.PeriodInUserId)
                .OnDelete(DeleteBehavior.Restrict);

            builder.HasOne(x => x.Indicator)
                .WithMany()
                .HasForeignKey(x => x.IndicatorId)
                .OnDelete(DeleteBehavior.Restrict);

            // İndeksler - Performans optimizasyonu
            builder.HasIndex(x => new { x.PeriodId, x.PeriodInUserId });
            builder.HasIndex(x => new { x.PeriodInUserId, x.IndicatorId })
                .IsUnique()
                .HasFilter("[IndicatorId] IS NOT NULL AND [IsActive] = 1");
            builder.HasIndex(x => x.Status);
        }
    }
}
