using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure.Persistence.Configurations;

namespace PYS.Infrastructure.Persistence.Configurations
{
    public class CustomAppRoleConfiguration : AppRoleConfiguration
    {
        public override void Configure(EntityTypeBuilder<AppRole> builder)
        {
            base.Configure(builder);

            builder.HasData(
                // 1. Admin
                new AppRole
                {
                    Id = Guid.Parse("55929733-870B-4895-B62B-B9249FA86A06"),
                    Name = "Admin",
                    NormalizedName = "ADMIN",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 2. Kullanıcı
                new AppRole
                {
                    Id = Guid.Parse("A5D3B6F8-1C2E-4D3F-9A8B-7C6E5D4F3A2B"),
                    Name = "Kullanıcı",
                    NormalizedName = "KULLANICI",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 3. Kademelendirme Yöneticisi (Mevcut ID kullanılıyor)
                new AppRole
                {
                    Id = Guid.Parse("55929733-870B-4895-B62B-B9249FA86A04"),
                    Name = "Kademelendirme Yöneticisi",
                    NormalizedName = "KADEMELENDIRME YONETICISI",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 4. Director
                new AppRole
                {
                    Id = Guid.Parse("C8F5D3A1-4B6C-5D7E-9F8A-3B4C5D6E7F8A"),
                    Name = "Director",
                    NormalizedName = "DIRECTOR",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 5. Manager
                new AppRole
                {
                    Id = Guid.Parse("D9A6E4B2-5C7D-6E8F-1A9B-4C5D6E7F8A9B"),
                    Name = "Manager",
                    NormalizedName = "MANAGER",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 6. Executive
                new AppRole
                {
                    Id = Guid.Parse("E1B7F5C3-6D8E-7F9A-2B1C-5D6E7F8A9B1C"),
                    Name = "Executive",
                    NormalizedName = "EXECUTIVE",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                },
                // 7. Staff
                new AppRole
                {
                    Id = Guid.Parse("F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D"),
                    Name = "Staff",
                    NormalizedName = "STAFF",
                    ConcurrencyStamp = Guid.NewGuid().ToString(),
                }
            );
        }
    }
}
