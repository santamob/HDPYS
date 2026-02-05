using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using PYS.Infrastructure.Persistence.Authentication;
using PYS.Infrastructure.Persistence.Context;
using PYS.Infrastructure.Persistence.Identity.Describers;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure.Persistence.UnitOfWorks;
using PYS.Core.Application.Interfaces.Authentication;
using PYS.Core.Application.Interfaces.Context;

public static class Registration
{
    public static void AddPersistence(this IServiceCollection services, IConfiguration configuration)
    {
        // App Context
        services.AddDbContext<AppDbContext>(options =>
    options.UseSqlServer(configuration.GetConnectionString("DefaultConnection")));

        // SAPDbContext - SAP işlemleri için
        services.AddDbContext<SAPDbContext>(options =>
            options.UseSqlServer(configuration.GetConnectionString("SF_APPSConnection")));

        // UnitOfWork kayıtları
        services.AddScoped<IUnitOfWork<IAppDbContext>, UnitOfWork<AppDbContext, IAppDbContext>>();
        services.AddScoped<IUnitOfWork<ISAPDbContext>, UnitOfWork<SAPDbContext, ISAPDbContext>>();

        services.AddIdentityCore<AppUser>(options =>
        {
            options.Password.RequireDigit = true;
            options.Password.RequiredLength = 8;
            options.Password.RequireNonAlphanumeric = true;
            options.Password.RequireUppercase = true;
            options.Password.RequireLowercase = true;
            options.User.RequireUniqueEmail = true;
            options.Lockout.MaxFailedAccessAttempts = 5;
            options.Lockout.DefaultLockoutTimeSpan = TimeSpan.FromMinutes(15);
            options.SignIn.RequireConfirmedEmail = true;
        })
        .AddRoles<AppRole>()
        .AddEntityFrameworkStores<AppDbContext>()
        .AddDefaultTokenProviders()
        .AddSignInManager<SignInManager<AppUser>>()
        .AddErrorDescriber<CustomIdentityErrorDescriber>();

        services.ConfigureApplicationCookie(options =>
        {
            options.Cookie.Name = "SantaFarmaDemo.Auth";
            options.LoginPath = "/Auth/Login";
            options.AccessDeniedPath = "/Auth/AccessDenied";
            options.ExpireTimeSpan = TimeSpan.FromMinutes(60);
        });

        services.Configure<SecurityStampValidatorOptions>(options =>
        {
            options.ValidationInterval = TimeSpan.FromMinutes(5);
        });
        services.AddScoped<IAuthenticationHelper, AuthenticationHelper>();
        services.AddScoped<ILDAPAuthService, LDAPAuthService>();
        services.AddSingleton<IHttpContextAccessor, HttpContextAccessor>();
    }
}