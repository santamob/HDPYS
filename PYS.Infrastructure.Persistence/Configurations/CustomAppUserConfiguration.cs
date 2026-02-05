using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure.Persistence.Configurations;

namespace PYS.Infrastructure.Persistence.Configurations
{
    public class CustomAppUserConfiguration : AppUserConfiguration
    {
        public override void Configure(EntityTypeBuilder<AppUser> builder)
        {
            base.Configure(builder);

            var admin = new AppUser
            {
                Id = Guid.Parse("037FFD4D-E033-4220-B044-C4C6EDDAD883"),
                UserName = "mobulut@santafarma.com.tr",
                NormalizedUserName = "MOBULUT@SANTAFARMA.COM.TR",
                Email = "mobulut@santafarma.com.tr",
                NormalizedEmail = "MOBULUT@SANTAFARMA.COM.TR",
                RegistrationNumber = 103539,
                PhoneNumber = "",
                PhoneNumberConfirmed = true,
                EmailConfirmed = true,
                SecurityStamp = Guid.NewGuid().ToString(),
                CreatedDate = DateTime.Now,
                CreatedIp = "::1",
                IsLDAP = false,
            };
            admin.PasswordHash = CreatePasswordHash(admin, "123456");
            builder.HasData(admin);
        }

        private string CreatePasswordHash(AppUser user, string password)
        {
            var passwordHasher = new PasswordHasher<AppUser>();
            return passwordHasher.HashPassword(user, password);
        }
    }
}
