using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using PYS.Web.Services;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Interfaces.Authentication;

namespace PYS.Web.Controllers
{
    public class AuthController(ILogger<AuthController> logger, IAuthenticationHelper authHelper, UserManager<AppUser> userManager, SignInManager<AppUser> signInManager, ILDAPAuthService ldapAuthService, IConfiguration configuration, LanguageService languageService) : Controller
    {
        /*
        [AllowAnonymous]
        [HttpGet]
        public string SifreHashle(string pass)
        {
            var user = new AppUser(); // Senin entity sınıfın
            var hasher = new PasswordHasher<AppUser>();
            return hasher.HashPassword(user, pass);
        }
        Manuel şifre eklemek için kullanılabilir.
        */
        [HttpGet]
        [AllowAnonymous]
        public IActionResult Login()
        {
            return View();
        }

        [AllowAnonymous]
        [HttpPost]
        public async Task<IActionResult> Login(UserLoginDto userLoginDto)
        {
            if (!ModelState.IsValid)
                return View(userLoginDto);

            var user = await userManager.FindByEmailAsync(userLoginDto.Email);

            if (user != null && await userManager.IsLockedOutAsync(user))
            {
                var lockoutEnd = await userManager.GetLockoutEndDateAsync(user);
                ModelState.AddModelError("", string.Format(languageService.GetKey("LoginLockedOutUntil").Value,
                    lockoutEnd?.LocalDateTime.ToString()));
                return View(userLoginDto);
            }

            bool isLdapAuthenticated = await ldapAuthService.LDAPAuthCheck(userLoginDto.Email, userLoginDto.Password);
            //bool isLdapAuthenticated = true;
            bool autoUserCreated = configuration.GetValue<bool>("ProjectSettings:AutoUserCreated");

            var sapUser = await authHelper.GetSapUserAsync(userLoginDto.Email);

            if (user == null && sapUser != null && isLdapAuthenticated && autoUserCreated)
            {
                await authHelper.AutoRegisterUserAsync(sapUser, userLoginDto.Email);
                user = await userManager.FindByEmailAsync(userLoginDto.Email);
            }

            if (user == null || !user.IsActive)
            {
                ModelState.AddModelError("", languageService.GetKey("LoginError").Value);
                return View(userLoginDto);
            }

            bool loginSuccess = user.IsLDAP
                ? isLdapAuthenticated
                : await authHelper.SignInWithPasswordAsync(user, userLoginDto);

            if (loginSuccess)
            {
                await userManager.ResetAccessFailedCountAsync(user);
                await userManager.SetLockoutEndDateAsync(user, null);

                if (user.IsLDAP)
                {
                    await signInManager.SignInAsync(user, userLoginDto.RememberMe);
                }

                return RedirectToAction("Index", "Home");
            }
            else
            {
                await userManager.AccessFailedAsync(user);

                if (await userManager.IsLockedOutAsync(user))
                {
                    var lockoutEnd = await userManager.GetLockoutEndDateAsync(user);
                    ModelState.AddModelError("", string.Format(languageService.GetKey("LoginLockedOut").Value,
                        lockoutEnd?.LocalDateTime.ToString()));
                }
                else
                {
                    int attemptsLeft = userManager.Options.Lockout.MaxFailedAccessAttempts
                                       - await userManager.GetAccessFailedCountAsync(user);
                    ModelState.AddModelError("", string.Format(languageService.GetKey("LoginError").Value, attemptsLeft));
                }

                return View(userLoginDto);
            }
        }

        [Authorize]
        [HttpGet]
        public async Task<IActionResult> Logout()
        {
            await signInManager.SignOutAsync();
            return RedirectToAction("Login");
        }

        [Authorize]
        [HttpGet]
        public IActionResult AccessDenied()
        {
            return View();
        }
    }
 }
