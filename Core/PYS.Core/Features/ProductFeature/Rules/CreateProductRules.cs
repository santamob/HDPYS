using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.ProductFeature.Rules
{
    public class CreateProductRules(ILanguageService languageService) : BaseRules
    {
        public Task<RuleResult> ProductShouldNotBeExist(Product? product)
        {
            if (product is not null)
                return Task.FromResult(Failure(languageService.GetKey("ProductShouldNotBeExist")));
            return Task.FromResult(Success());
        }
    }
}
