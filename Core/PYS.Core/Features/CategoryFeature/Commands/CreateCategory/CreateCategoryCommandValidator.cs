using FluentValidation;
using PYS.Core.Application.Interfaces.Localization;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory
{
    public class CreateCategoryCommandValidator : AbstractValidator<CreateCategoryCommandRequest>
    {
        private readonly ILanguageService languageService;

        public CreateCategoryCommandValidator(ILanguageService languageService = null)
        {
            this.languageService = languageService;
            RuleFor(x => x.Name)
                    .NotEmpty()
                    .MaximumLength(100)
                    .MinimumLength(2)
                    .WithName(languageService?.GetKey("Category") ?? "Category");
        }
    }
}
