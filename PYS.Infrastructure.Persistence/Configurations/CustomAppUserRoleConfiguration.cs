using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure.Persistence.Configurations;

namespace PYS.Infrastructure.Persistence.Configurations
{
    public class CustomAppUserRoleConfiguration : AppUserRoleConfiguration

    {
        public override void Configure(EntityTypeBuilder<AppUserRole> builder)
        {
            base.Configure(builder);

            builder.HasData(new AppUserRole
            {
                UserId = Guid.Parse("037FFD4D-E033-4220-B044-C4C6EDDAD883"),
                RoleId = Guid.Parse("55929733-870B-4895-B62B-B9249FA86A06"),
            });
        }
    }
}
