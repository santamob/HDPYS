using Microsoft.EntityFrameworkCore;
using SantaFarma.Architecture.Infrastructure.Persistence.Context;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;

namespace PYS.Infrastructure.Persistence.Context
{
    public partial class SAPDbContext : DbContext, ISAPDbContext
    {
        public SAPDbContext(DbContextOptions<SAPDbContext> options) : base(options)
        {
            Console.WriteLine("deneme");
        }
        public virtual DbSet<SapEmployeeList> SapEmployeeLists { get; set; }
        public virtual DbSet<SapEmployeeListLog> SapEmployeeListLogs { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.UseCollation("Turkish_CI_AS");

            modelBuilder.Entity<SapEmployeeList>(entity =>
            {
                entity.ToTable("SAP_EmployeeList");

                entity.HasIndex(e => e.Pernr, "IX_SAP_EmployeeList");

                entity.HasIndex(e => e.Persg, "IX_SAP_EmployeeList_1");

                entity.HasIndex(e => e.LogId, "IX_SAP_EmployeeList_2");

                entity.Property(e => e.Id).HasColumnName("ID");
                entity.Property(e => e.AccAsgmnt).HasMaxLength(20);
                entity.Property(e => e.AccAsgmntT).HasMaxLength(40);
                entity.Property(e => e.BegDa).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.CepTel).HasMaxLength(25);
                entity.Property(e => e.CreatedDate)
                    .HasDefaultValueSql("(getdate())")
                    .HasColumnType("datetime");
                entity.Property(e => e.Ename).HasMaxLength(50);
                entity.Property(e => e.EndDa).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.GbDat).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.IsTel).HasMaxLength(25);
                entity.Property(e => e.Isbas).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.Kademe).HasMaxLength(20);
                entity.Property(e => e.KademeT).HasMaxLength(100);
                entity.Property(e => e.LogId)
                    .HasDefaultValue(0)
                    .HasColumnName("LogID");
                entity.Property(e => e.MMail)
                    .HasMaxLength(100)
                    .HasColumnName("M_Mail");
                entity.Property(e => e.MPernr)
                    .HasDefaultValue(0)
                    .HasColumnName("M_Pernr");
                entity.Property(e => e.Mail).HasMaxLength(100);
                entity.Property(e => e.Merni).HasMaxLength(12);
                entity.Property(e => e.Nachn).HasMaxLength(25);
                entity.Property(e => e.Oid)
                    .HasDefaultValue(0)
                    .HasColumnName("OID");
                entity.Property(e => e.Orgtx).HasMaxLength(40);
                entity.Property(e => e.PersData)
                    .HasMaxLength(1)
                    .HasDefaultValueSql("((0))");
                entity.Property(e => e.Persg)
                    .HasMaxLength(1)
                    .HasComment("1-Beyaz Yaka\r\n2-Mavi Yaka\r\n3-Stajyer");
                entity.Property(e => e.Persk)
                    .HasMaxLength(2)
                    .HasComment("1-BelrszSür.Merkez\r\n2-BelrszSür.Fabrika\r\n3-BelrszSür.Saha\r\n4-BelirliSür.Merkez\r\n5-BelirliSür.Fabrika\r\n6-BelirliSür.Saha\r\n");
                entity.Property(e => e.Plstx).HasMaxLength(40);
                entity.Property(e => e.Sid)
                    .HasDefaultValue(0)
                    .HasColumnName("SID");
                entity.Property(e => e.Typ)
                    .HasDefaultValue(0)
                    .HasComment("1-Yeni, 2-Güncelleme, 3-Silme");
                entity.Property(e => e.TypT).HasMaxLength(500);
                entity.Property(e => e.UpdatedDate)
                    .HasDefaultValue(new DateTime(1900, 1, 1, 0, 0, 0, 0, DateTimeKind.Unspecified))
                    .HasColumnType("datetime");
                entity.Property(e => e.UsrId)
                    .HasMaxLength(30)
                    .HasColumnName("UsrID");
                entity.Property(e => e.Vorna).HasMaxLength(25);
            });

            modelBuilder.Entity<SapEmployeeListLog>(entity =>
            {
                entity.ToTable("SAP_EmployeeList_Log");

                entity.HasIndex(e => e.Pernr, "IX_SAP_EmployeeList_Log");

                entity.HasIndex(e => new { e.Pernr, e.LogId }, "IX_SAP_EmployeeList_Log_1");

                entity.Property(e => e.Id).HasColumnName("ID");
                entity.Property(e => e.AccAsgmnt).HasMaxLength(20);
                entity.Property(e => e.AccAsgmntT).HasMaxLength(40);
                entity.Property(e => e.BegDa).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.CepTel).HasMaxLength(20);
                entity.Property(e => e.CreatedDate)
                    .HasDefaultValueSql("(getdate())")
                    .HasColumnType("datetime");
                entity.Property(e => e.Ename).HasMaxLength(50);
                entity.Property(e => e.EndDa).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.GbDat).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.IsTel).HasMaxLength(20);
                entity.Property(e => e.Isbas).HasDefaultValue(new DateOnly(1900, 1, 1));
                entity.Property(e => e.Kademe).HasMaxLength(20);
                entity.Property(e => e.KademeT).HasMaxLength(100);
                entity.Property(e => e.LogId)
                    .HasDefaultValue(0)
                    .HasColumnName("LogID");
                entity.Property(e => e.MMail)
                    .HasMaxLength(100)
                    .HasColumnName("M_Mail");
                entity.Property(e => e.MPernr)
                    .HasDefaultValue(0)
                    .HasColumnName("M_Pernr");
                entity.Property(e => e.Mail).HasMaxLength(100);
                entity.Property(e => e.Merni).HasMaxLength(12);
                entity.Property(e => e.Nachn).HasMaxLength(25);
                entity.Property(e => e.Oid)
                    .HasDefaultValue(0)
                    .HasColumnName("OID");
                entity.Property(e => e.Orgtx).HasMaxLength(40);
                entity.Property(e => e.PersData)
                    .HasMaxLength(1)
                    .HasDefaultValueSql("((0))");
                entity.Property(e => e.Persg)
                    .HasMaxLength(1)
                    .HasComment("1-Beyaz Yaka, 2-Mavi Yaka, 3-Stajyer");
                entity.Property(e => e.Persk).HasMaxLength(2);
                entity.Property(e => e.Plstx).HasMaxLength(40);
                entity.Property(e => e.Sid)
                    .HasDefaultValue(0)
                    .HasColumnName("SID");
                entity.Property(e => e.Typ).HasDefaultValue(0);
                entity.Property(e => e.TypT).HasMaxLength(500);
                entity.Property(e => e.UpdatedDate)
                    .HasDefaultValue(new DateTime(1900, 1, 1, 0, 0, 0, 0, DateTimeKind.Unspecified))
                    .HasColumnType("datetime");
                entity.Property(e => e.UsrId)
                    .HasMaxLength(30)
                    .HasColumnName("UsrID");
                entity.Property(e => e.Vorna).HasMaxLength(25);
            });

            OnModelCreatingPartial(modelBuilder); 
        }
        partial void OnModelCreatingPartial(ModelBuilder modelBuilder);

    }
}
