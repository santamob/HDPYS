using FluentValidation;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal
{
    /// <summary>
    /// Hedef güncelleme komutu validasyonu
    /// </summary>
    public class UpdateGoalCommandValidator : AbstractValidator<UpdateGoalCommandRequest>
    {
        public UpdateGoalCommandValidator()
        {
            RuleFor(x => x.Id)
                .NotEmpty()
                .WithMessage("Hedef ID zorunludur.");

            RuleFor(x => x.GoalTitle)
                .NotEmpty()
                .WithMessage("Hedef başlığı zorunludur.")
                .MaximumLength(500)
                .WithMessage("Hedef başlığı en fazla 500 karakter olabilir.");

            RuleFor(x => x.GoalDescription)
                .MaximumLength(4000)
                .WithMessage("Hedef açıklaması en fazla 4000 karakter olabilir.");

            RuleFor(x => x.TargetValue)
                .GreaterThan(0)
                .WithMessage("Hedef değeri 0'dan büyük olmalıdır.");

            RuleFor(x => x.TargetUnit)
                .MaximumLength(50)
                .WithMessage("Hedef birimi en fazla 50 karakter olabilir.");

            RuleFor(x => x.Weight)
                .InclusiveBetween(1, 100)
                .WithMessage("Ağırlık 1 ile 100 arasında olmalıdır.");

            RuleFor(x => x.EmployeeNote)
                .MaximumLength(2000)
                .WithMessage("Çalışan notu en fazla 2000 karakter olabilir.");

            RuleFor(x => x)
                .Must(x => x.EndDate == null || x.StartDate == null || x.EndDate >= x.StartDate)
                .WithMessage("Bitiş tarihi, başlangıç tarihinden önce olamaz.");
        }
    }
}
