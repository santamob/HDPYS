using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddRemainingRolesManual : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            // 1. Kullanıcı rolü - yoksa ekle
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Kullanıcı')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('A5D3B6F8-1C2E-4D3F-9A8B-7C6E5D4F3A2B', 'Kullanıcı', 'KULLANICI', NEWID())
                END
            ");

            // 2. Kademelendirme Yöneticisi rolü - yoksa ekle (Mevcut ID kullanılıyor)
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Kademelendirme Yöneticisi')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('55929733-870B-4895-B62B-B9249FA86A04', 'Kademelendirme Yöneticisi', 'KADEMELENDIRME YONETICISI', NEWID())
                END
            ");

            // 3. Director rolü - yoksa ekle
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Director')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('C8F5D3A1-4B6C-5D7E-9F8A-3B4C5D6E7F8A', 'Director', 'DIRECTOR', NEWID())
                END
            ");

            // 4. Manager rolü - yoksa ekle
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Manager')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('D9A6E4B2-5C7D-6E8F-1A9B-4C5D6E7F8A9B', 'Manager', 'MANAGER', NEWID())
                END
            ");

            // 5. Executive rolü - yoksa ekle
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Executive')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('E1B7F5C3-6D8E-7F9A-2B1C-5D6E7F8A9B1C', 'Executive', 'EXECUTIVE', NEWID())
                END
            ");

            // 6. Staff rolü - yoksa ekle
            migrationBuilder.Sql(@"
                IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Staff')
                BEGIN
                    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
                    VALUES ('F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D', 'Staff', 'STAFF', NEWID())
                END
            ");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            // Rolleri geri silme - dikkatli olun!
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Kullanıcı'");
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Kademelendirme Yöneticisi'");
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Director'");
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Manager'");
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Executive'");
            migrationBuilder.Sql("DELETE FROM AspNetRoles WHERE Name = 'Staff'");
        }
    }
}
