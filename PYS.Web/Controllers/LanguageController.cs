using Microsoft.AspNetCore.Localization;
using Microsoft.AspNetCore.Mvc;

namespace PYS.Web.Controllers
{
    public class LanguageController : Controller
    {
        [HttpPost]
        public IActionResult ChangeLanguage(string culture, string returnUrl)
        {
            try
            {
                Response.Cookies.Append(
                               CookieRequestCultureProvider.DefaultCookieName,
                               CookieRequestCultureProvider.MakeCookieValue(new RequestCulture(culture)),
                               new CookieOptions { Expires = DateTimeOffset.UtcNow.AddYears(1) }
                           );
                return LocalRedirect(returnUrl);

            }
            catch (Exception)
            {

                throw;
            }
        }
    }
}
