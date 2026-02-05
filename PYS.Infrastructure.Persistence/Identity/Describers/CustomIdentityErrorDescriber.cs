using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Localization;

namespace PYS.Infrastructure.Persistence.Identity.Describers
{
    public class CustomIdentityErrorDescriber : IdentityErrorDescriber
    {
        private readonly string? culture;

        public CustomIdentityErrorDescriber(IHttpContextAccessor httpContextAccessor)
        {
            culture = httpContextAccessor.HttpContext?.Features.Get<IRequestCultureFeature>()?.RequestCulture.Culture.TwoLetterISOLanguageName;
        }

        public override IdentityError PasswordRequiresUniqueChars(int uniqueChars)
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordRequiresUniqueChars", Description = $"Parola en az {uniqueChars} farklı karakter içermelidir." }
                : base.PasswordRequiresUniqueChars(uniqueChars);
        }

        public override IdentityError DuplicateEmail(string email)
        {
            return culture == "tr"
                ? new IdentityError { Code = "DuplicateEmail", Description = $"Bu email adresine ait bir hesap bulunmaktadır." }
                : base.DuplicateEmail(email);
        }

        public override IdentityError DuplicateUserName(string userName)
        {
            return culture == "tr"
                ? new IdentityError { Code = "DuplicateUserName", Description = $"{userName} kullanıcı adına sahip bir hesap zaten mevcut." }
                : base.DuplicateUserName(userName);
        }

        public override IdentityError DuplicateRoleName(string role)
        {
            return culture == "tr"
                ? new IdentityError { Code = "DuplicateRoleName", Description = $"'{role}' isminde bir rol zaten mevcut." }
                : base.DuplicateRoleName(role);
        }

        public override IdentityError InvalidEmail(string email)
        {
            return culture == "tr"
                ? new IdentityError { Code = "InvalidEmail", Description = $"Belirtilen email adresi ({email}) geçersizdir." }
                : base.InvalidEmail(email);
        }

        public override IdentityError InvalidRoleName(string role)
        {
            return culture == "tr"
                ? new IdentityError { Code = "InvalidRoleName", Description = $"Belirtilen rol ismi ({role}) geçersizdir." }
                : base.InvalidRoleName(role);
        }

        public override IdentityError InvalidUserName(string userName)
        {
            return culture == "tr"
                ? new IdentityError { Code = "InvalidUserName", Description = $"Belirtilen kullanıcı adı ({userName}) geçersizdir." }
                : base.InvalidUserName(userName);
        }

        public override IdentityError PasswordTooShort(int length)
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordTooShort", Description = $"Parola çok kısa. En az {length} karakter olmalıdır." }
                : base.PasswordTooShort(length);
        }

        public override IdentityError UserAlreadyInRole(string role)
        {
            return culture == "tr"
                ? new IdentityError { Code = "UserAlreadyInRole", Description = $"Kullanıcı zaten '{role}' rolüne sahip." }
                : base.UserAlreadyInRole(role);
        }

        public override IdentityError UserNotInRole(string role)
        {
            return culture == "tr"
                ? new IdentityError { Code = "UserNotInRole", Description = $"Kullanıcı '{role}' rolüne sahip değil." }
                : base.UserNotInRole(role);
        }

        public override IdentityError ConcurrencyFailure()
        {
            return culture == "tr"
                ? new IdentityError { Code = "ConcurrencyFailure", Description = "Birden fazla kullanıcı aynı anda veri değiştirmeye çalıştı. Değişiklikler geri alındı." }
                : base.ConcurrencyFailure();
        }

        public override IdentityError LoginAlreadyAssociated()
        {
            return culture == "tr"
                ? new IdentityError { Code = "LoginAlreadyAssociated", Description = "Bu oturum zaten bir hesapla ilişkilendirilmiş." }
                : base.LoginAlreadyAssociated();
        }

        public override IdentityError PasswordMismatch()
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordMismatch", Description = "Parola uyuşmuyor." }
                : base.PasswordMismatch();
        }

        public override IdentityError PasswordRequiresDigit()
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordRequiresDigit", Description = "Parola en az bir rakam içermelidir." }
                : base.PasswordRequiresDigit();
        }

        public override IdentityError PasswordRequiresNonAlphanumeric()
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordRequiresNonAlphanumeric", Description = "Parola en az bir alfasayısal olmayan karakter içermelidir." }
                : base.PasswordRequiresNonAlphanumeric();
        }

        public override IdentityError PasswordRequiresUpper()
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordRequiresUpper", Description = "Parola en az bir büyük harf içermelidir." }
                : base.PasswordRequiresUpper();
        }

        public override IdentityError PasswordRequiresLower()
        {
            return culture == "tr"
                ? new IdentityError { Code = "PasswordRequiresLower", Description = "Parola en az bir küçük harf içermelidir." }
                : base.PasswordRequiresLower();
        }

        public override IdentityError RecoveryCodeRedemptionFailed()
        {
            return culture == "tr"
                ? new IdentityError { Code = "RecoveryCodeRedemptionFailed", Description = "Kurtarma kodu geçersiz." }
                : base.RecoveryCodeRedemptionFailed();
        }

        public override IdentityError UserAlreadyHasPassword()
        {
            return culture == "tr"
                ? new IdentityError { Code = "UserAlreadyHasPassword", Description = "Kullanıcının zaten bir parolası var." }
                : base.UserAlreadyHasPassword();
        }

        public override IdentityError DefaultError()
        {
            return culture == "tr"
                ? new IdentityError { Code = "DefaultError", Description = "Bilinmeyen bir hata oluştu." }
                : base.DefaultError();
        }

        public override IdentityError UserLockoutNotEnabled()
        {
            return culture == "tr"
                ? new IdentityError { Code = "UserLockoutNotEnabled", Description = "Bu kullanıcı için hesap kilitleme aktif değil." }
                : base.UserLockoutNotEnabled();
        }
    }

}
