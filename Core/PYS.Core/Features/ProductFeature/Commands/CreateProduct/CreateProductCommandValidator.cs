using FluentValidation;
using PYS.Core.Application.Interfaces.Localization;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.ProductFeature.Commands.CreateProduct
{
    public class CreateProductCommandValidator : AbstractValidator<CreateProductCommandRequest>
    {
        private readonly ILanguageService languageService;

        public CreateProductCommandValidator(ILanguageService languageService = null)
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
