using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Rules
{
    public class CreateUserRules(ILanguageService languageService) : BaseRules
    {
        public Task<RuleResult> UserShouldNotBeExist(AppUser? user)
        {
            if (user is not null)
                return Task.FromResult(Failure(languageService.GetKey("UserShouldNotBeExist")));
            return Task.FromResult(Success());
        }

        public Task<RuleResult> EmailOrPasswordShouldNotBeInvalid(AppUser? user, bool checkPassword)
        {
            if (user is null || !checkPassword)
                return Task.FromResult(Failure(languageService.GetKey("EmailOrPasswordShouldNotBeInvalid")));
            return Task.FromResult(Success());
        }

        public Task<RuleResult> EmailAddressShouldBeValid(AppUser? user)
        {
            if (user is null)
                return Task.FromResult(Failure(languageService.GetKey("EmailAddressShouldBeValid")));
            return Task.FromResult(Success());
        }
    }
}
