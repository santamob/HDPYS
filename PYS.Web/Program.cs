using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.CookiePolicy;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Localization;
using NToastNotify;
using PYS.Web.Services;
using SantaFarma.Architecture.Core.Application;
using SantaFarma.Architecture.Core.Application.Exceptions;
using SantaFarma.Architecture.Core.Application.UserActivity;
using SantaFarma.Architecture.Core.Domain.Entities;
using SantaFarma.Architecture.Infrastructure;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using Serilog.Context;
using System.Globalization;
using System.Reflection;
using System.Security.Claims;

var builder = WebApplication.CreateBuilder(args);
var isDevelopment = builder.Environment.IsDevelopment();

var applicationAssembly = typeof(GetEmployeeByEmailFromSapHandler).Assembly;

builder.Services.AddSingleton<PYS.Core.Application.Interfaces.Localization.ILanguageService, LanguageService>(); builder.Services.AddLogging();
builder.Services.AddPersistence(builder.Configuration);
builder.Services.AddAuthentication(options =>
{
    options.DefaultScheme = IdentityConstants.ApplicationScheme;
    options.DefaultSignInScheme = IdentityConstants.ExternalScheme;
})
.AddIdentityCookies();
builder.Services.AddBaseInfrastructure(builder.Configuration);
builder.Services.AddBaseApplication(new[] { applicationAssembly });

//Localizer
builder.Services.AddSingleton<LanguageService>();
builder.Services.AddLocalization(options => options.ResourcesPath = "Resources");
builder.Services.AddMvc().AddMvcLocalization().AddDataAnnotationsLocalization(options => options.DataAnnotationLocalizerProvider = (type, factory) =>
{
    var assemblyName = new AssemblyName(typeof(Lang).GetTypeInfo().Assembly.FullName);
    return factory.Create(nameof(Lang), assemblyName.Name);
});
builder.Services.Configure<RequestLocalizationOptions>(options =>
{

    CultureInfo[] cultures = new CultureInfo[]
    {
                new("tr-TR"),
                new("en-US"),
    };
    options.DefaultRequestCulture = new RequestCulture("tr-TR");
    options.SupportedCultures = cultures;
    options.SupportedUICultures = cultures;
});

//Session Configuration
builder.Services.AddSession(options =>
{
    options.Cookie.Name = "PYS.Session";
    options.Cookie.HttpOnly = true;
    options.Cookie.SameSite = isDevelopment ? SameSiteMode.None : SameSiteMode.Strict;
    options.Cookie.SecurePolicy = isDevelopment ? CookieSecurePolicy.None : CookieSecurePolicy.Always;
    options.IdleTimeout = TimeSpan.FromMinutes(20);
});

// Toast Notification
builder.Services.AddControllersWithViews().AddNToastNotifyToastr(new ToastrOptions
{
    PositionClass = ToastPositions.TopRight,
    TimeOut = 3000,
    ProgressBar = true,
});

builder.Services.ConfigureApplicationCookie(options =>
{
    options.Cookie.Name = "PYS.Auth";
    options.Cookie.HttpOnly = true;
    options.Cookie.SameSite = isDevelopment ? SameSiteMode.Lax : SameSiteMode.Strict;
    options.Cookie.SecurePolicy = isDevelopment ? CookieSecurePolicy.SameAsRequest : CookieSecurePolicy.Always;

    options.LoginPath = "/Auth/Login";
    options.LogoutPath = "/Auth/Logout";
    options.AccessDeniedPath = "/Auth/AccessDenied";

    options.ExpireTimeSpan = TimeSpan.FromMinutes(60);
    options.SlidingExpiration = true;

    options.Events = new CookieAuthenticationEvents
    {
        OnValidatePrincipal = async context =>
        {
            var signInManager = context.HttpContext.RequestServices
                .GetRequiredService<SignInManager<AppUser>>();

            await signInManager.ValidateSecurityStampAsync(context.Principal);

            var userManager = context.HttpContext.RequestServices
                .GetRequiredService<UserManager<AppUser>>();

            var user = await userManager.GetUserAsync(context.Principal);

            if (user == null ||
               (await userManager.IsLockedOutAsync(user))) 
            {
                context.RejectPrincipal();
                await context.HttpContext.SignOutAsync(
                    IdentityConstants.ApplicationScheme);
            }
        }
    };
});

builder.Services.Configure<SecurityStampValidatorOptions>(options =>
{
    options.ValidationInterval = TimeSpan.FromMinutes(5);
});

builder.Services.AddAuthorization(options =>
{
    options.DefaultPolicy = new AuthorizationPolicyBuilder()
        .RequireAuthenticatedUser()
        .Build();

    options.AddPolicy("HighSecurity", policy =>
    {
        policy.RequireAuthenticatedUser();
        policy.RequireAssertion(context =>
        {
            var claim = context.User.FindFirst(c => c.Type == ClaimTypes.AuthenticationMethod);
            return claim?.Value == "2fa";
        });
    });
});

builder.Services.Configure<CookiePolicyOptions>(options =>
{
    options.MinimumSameSitePolicy = isDevelopment ? SameSiteMode.Lax : SameSiteMode.Strict;
    options.Secure = isDevelopment ? CookieSecurePolicy.SameAsRequest : CookieSecurePolicy.Always;
    options.HttpOnly = HttpOnlyPolicy.Always;
});



var app = builder.Build();

if (!app.Environment.IsDevelopment())
{
    //app.UseMiddleware<UserActivityMiddleware>();
    app.UseMiddleware<ExceptionMiddleware>();
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}
app.Use(async (context, next) =>
{
    context.Response.Headers.Append("X-Content-Type-Options", "nosniff");
    context.Response.Headers.Append("X-Frame-Options", "DENY");
    context.Response.Headers.Append("X-XSS-Protection", "1; mode=block");
    context.Response.Headers.Append("Referrer-Policy", "strict-origin-when-cross-origin");

    if (!isDevelopment)
    {
        context.Response.Headers.Append("Content-Security-Policy",
            "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:");
    }

    await next();
});
app.UseRequestLocalization();
app.UseStaticFiles();
app.UseCookiePolicy();
app.UseRouting();

app.UseAuthentication();
app.UseAuthorization();
app.UseSession();

app.Use(async (context, next) =>
{
    var username = context.User?.Identity?.IsAuthenticated != null || true ? context.User.Identity.Name : null;
    LogContext.PushProperty("UserID", username);
    LogContext.PushProperty("UserIP", context.Connection.RemoteIpAddress);
    await next();
});

app.UseNToastNotify();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}")
    .RequireAuthorization();

app.Run();
