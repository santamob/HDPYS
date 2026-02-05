using Microsoft.Extensions.Localization;
using PYS.Core.Application.Interfaces.Localization;
using System.Reflection;

namespace PYS.Web.Services
{
    public class Lang
    {

    }
    public class LanguageService : ILanguageService
    {
        private readonly IStringLocalizer localizer;

        public LanguageService(IStringLocalizerFactory factory)
        {
            var type = typeof(Lang);
            var assemblyName = new AssemblyName(type.GetTypeInfo().Assembly.FullName);

            localizer = factory.Create(nameof(Lang), assemblyName.Name);
        }
        public LocalizedString GetKey(string key)
        {
            return localizer[key];
        }

        string ILanguageService.GetKey(string key)
        {
            return GetKey(key);
        }
    }
}
