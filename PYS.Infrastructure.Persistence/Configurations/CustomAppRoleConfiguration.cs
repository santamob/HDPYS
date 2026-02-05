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

            builder.HasData(new AppRole
            {
                Id = Guid.Parse("55929733-870B-4895-B62B-B9249FA86A06"),
                Name = "Admin",
                NormalizedName = "ADMIN",
                ConcurrencyStamp = Guid.NewGuid().ToString(),
            });
        }
    }
}
