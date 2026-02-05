using Microsoft.AspNetCore.Identity;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Dtos
{
    public class UserCreateDto
    {
        public int RegistrationNumber { get; set; }
        public string? FirstName { get; set; }
        public string? LastName { get; set; }
        public string? Position { get; set; }
        public string Unit { get; set; }
        public string Email { get; set; }
        public string? PhoneNumber { get; set; }
        public string Password { get; set; }
        public bool LockoutEnabled { get; set; } = false;
        public bool RegisterPolicy { get; set; }
        public string? ProfilePhotoUrl { get; set; }
        public bool IsLDAP { get; set; } = true;
        public List<IdentityError>? Errors { get; set; }
        public List<AppRole> Roles { get; set; }
        public List<Guid>? SelectedRoles { get; set; }
    }
}
