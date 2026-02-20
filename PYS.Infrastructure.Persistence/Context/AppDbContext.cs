using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure.Persistence.Context;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using System.Reflection;
using Bogus;
using System.Reflection.Emit;

namespace PYS.Infrastructure.Persistence.Context
{
    public class AppDbContext : BaseDbContext<AppDbContext>, IAppDbContext
    {

        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
        {

        }
        public DbSet<Category> Categories { get; set; }
        public DbSet<Product> Products { get; set; }
        public DbSet<FormTypes> FormTypes { get; set; }

        public DbSet<Periods> Periods { get; set; }
        public DbSet<Location> Locations { get; set; }

        public DbSet<FormTypesPeriods> FormTypesPeriods { get; set; }
        public DbSet<LocationPeriods> LocationPeriods { get; set; }

        public DbSet<Indicator> Indicators { get; set; }

        public DbSet<Stage> Stages { get; set; }

        public DbSet<Forms> Forms { get; set; }
        public DbSet<FormDetails> FormDetails { get; set; }

        public DbSet<PotentialEvaluation> PotentialEvaluations { get; set; }

        public DbSet<PotentialEvaluationDetail> PotentialEvaluationDetails { get; set; }

        public DbSet<PeriodInUser> PeriodInUsers { get; set; }

        public DbSet<EmployeeGoal> EmployeeGoals { get; set; }

        protected override void OnModelCreating(ModelBuilder builder)
        {
            base.OnModelCreating(builder);

            //PK 
            builder.Entity<FormTypesPeriods>()
                .HasKey(fp => new { fp.FormTypesId, fp.PeriodsId }); 

            //PK
            builder.Entity<LocationPeriods>()
                .HasKey(lp => new { lp.LocationsId, lp.PeriodsId });

            //FormTypesId FK
            builder.Entity<FormTypesPeriods>()
                .HasOne(fp => fp.FormType)
                .WithMany(f => f.FormTypesPeriods)
                .HasForeignKey(fp => fp.FormTypesId)
                 .OnDelete(DeleteBehavior.Cascade); 

            builder.Entity<FormTypesPeriods>()
                .HasOne(fp => fp.Period)
                .WithMany(p => p.FormTypesPeriods)
                .HasForeignKey(fp => fp.PeriodsId)
                .OnDelete(DeleteBehavior.Cascade); 

            builder.Entity<LocationPeriods>()
                .HasOne(lp => lp.Location)
                .WithMany(l => l.LocationPeriods)
                .HasForeignKey(lp => lp.LocationsId)
                .OnDelete(DeleteBehavior.Cascade); 

            builder.Entity<LocationPeriods>()
                .HasOne(lp => lp.Period)
                .WithMany(p => p.LocationPeriods)
                .HasForeignKey(lp => lp.PeriodsId)
                .OnDelete(DeleteBehavior.Cascade); 


            foreach (var relationship in builder.Model.GetEntityTypes().SelectMany(e => e.GetForeignKeys()))
            {
                relationship.DeleteBehavior = DeleteBehavior.Cascade;
            }

            builder.ApplyConfigurationsFromAssembly(Assembly.GetExecutingAssembly());

            // EmployeeGoals: AppUser FK'larını Restrict yap (SQL Server cascade cycle önlemi)
            var employeeGoalEntity = builder.Model.FindEntityType(typeof(EmployeeGoal));
            if (employeeGoalEntity != null)
            {
                foreach (var fk in employeeGoalEntity.GetForeignKeys())
                {
                    if (fk.PrincipalEntityType.ClrType == typeof(AppUser))
                    {
                        fk.DeleteBehavior = DeleteBehavior.Restrict;
                    }
                }
            }
        }
    }
}