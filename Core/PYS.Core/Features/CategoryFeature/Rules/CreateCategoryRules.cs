using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.CategoryFeature.Rules
{
    public class CreateCategoryRules(ILanguageService languageService) : BaseRules
    {
        public Task<RuleResult> CategoryShouldNotBeExist(Category? category)
        {
            if(category is not null)
                return Task.FromResult(Failure(languageService.GetKey("CategoryShouldNotBeExist")));
            return Task.FromResult(Success());
        }
    }
}
