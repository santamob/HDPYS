using FluentValidation;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal
{
    /// <summary>
    /// Hedef oluşturma komutu validasyonu.
    /// Dönem seçimi, hedef başlığı, ağırlık gibi alanları kontrol eder.
    /// </summary>
    public class CreateGoalCommandValidator : AbstractValidator<CreateGoalCommandRequest>
    {
        public CreateGoalCommandValidator()
        {
            RuleFor(x => x.PeriodId)
                .NotEmpty()
                .WithMessage("Dönem seçimi zorunludur.");

            RuleFor(x => x.Goals)
                .NotEmpty()
                .WithMessage("En az bir hedef eklenmelidir.");

            RuleForEach(x => x.Goals).ChildRules(goal =>
            {
                goal.RuleFor(g => g.GoalTitle)
                    .NotEmpty()
                    .WithMessage("Hedef başlığı zorunludur.")
                    .MaximumLength(500)
                    .WithMessage("Hedef başlığı en fazla 500 karakter olabilir.");

                goal.RuleFor(g => g.GoalDescription)
                    .MaximumLength(4000)
                    .WithMessage("Hedef açıklaması en fazla 4000 karakter olabilir.");

                goal.RuleFor(g => g.TargetValue)
                    .GreaterThan(0)
                    .WithMessage("Hedef değeri 0'dan büyük olmalıdır.");

                goal.RuleFor(g => g.TargetUnit)
                    .MaximumLength(50)
                    .WithMessage("Hedef birimi en fazla 50 karakter olabilir.");

                goal.RuleFor(g => g.Weight)
                    .InclusiveBetween(1, 100)
                    .WithMessage("Ağırlık 1 ile 100 arasında olmalıdır.");

                goal.RuleFor(g => g.EmployeeNote)
                    .MaximumLength(2000)
                    .WithMessage("Çalışan notu en fazla 2000 karakter olabilir.");

                goal.RuleFor(g => g)
                    .Must(g => g.EndDate == null || g.StartDate == null || g.EndDate >= g.StartDate)
                    .WithMessage("Bitiş tarihi, başlangıç tarihinden önce olamaz.");
            });
        }
    }
}
