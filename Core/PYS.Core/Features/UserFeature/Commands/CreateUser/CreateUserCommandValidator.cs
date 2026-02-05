using FluentValidation;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Core.Application.Features.UserFeature.Commands.CreateUser
{
    public class CreateUserCommandValidator : AbstractValidator<CreateUserCommandRequest>
    {
        private readonly ILanguageService languageService;

        public CreateUserCommandValidator(ILanguageService languageService = null)
        {
            this.languageService = languageService;

            RuleFor(x => x.Email)
                .NotEmpty()
                .MaximumLength(60)
                .EmailAddress()
                .MinimumLength(8)
                .WithName(languageService?.GetKey("Eposta") ?? "Eposta");

            RuleFor(x => x.Password)
                .NotEmpty()
                .MinimumLength(8)
                .WithName(languageService?.GetKey("Password") ?? "Password");
        }
    }
}
