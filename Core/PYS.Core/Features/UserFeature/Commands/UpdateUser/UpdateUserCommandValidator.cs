using FluentValidation;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUser
{
    public class UpdateUserCommandValidator : AbstractValidator<UpdateUserCommandRequest>
    {
        private readonly ILanguageService languageService;

        public UpdateUserCommandValidator(ILanguageService languageService = null)
        {
            this.languageService = languageService;

            RuleFor(x => x.Password)
                .NotEmpty()
                .MinimumLength(8)
                .WithName(languageService?.GetKey("Password") ?? "Password");
        }
    }
}
