using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FluentValidation;
using PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Core.Application.Features.FormTypesFeature.Commands.Create
{
    public class CreateFormTypeCommandValidator : AbstractValidator<CreateFormTypeCommandRequest>
    {
        private readonly ILanguageService languageService;

        public CreateFormTypeCommandValidator(ILanguageService languageService = null)
        {
            this.languageService = languageService;
            RuleFor(x => x.FormTypeName)
                    .NotEmpty()
                    .MaximumLength(100)
                    .MinimumLength(2)
                    .WithName(languageService?.GetKey("Category") ?? "Category");
        }
    }
}
