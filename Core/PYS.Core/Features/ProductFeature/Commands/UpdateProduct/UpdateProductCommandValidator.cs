using FluentValidation;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct
{
    public class UpdateProductCommandValidator : AbstractValidator<UpdateProductCommandRequest>
    {
        private readonly ILanguageService languageService;

        public UpdateProductCommandValidator(ILanguageService languageService = null)
        {
            this.languageService = languageService;
            RuleFor(x => x.Name)
                .NotEmpty()
                .MaximumLength(100)
                .MinimumLength(2)
                .WithName(languageService?.GetKey("Product") ?? "Product");
            RuleFor(x => x.Code)
                .NotEmpty()
               .WithName(languageService?.GetKey("Code") ?? "Code");
        }
    }
}
