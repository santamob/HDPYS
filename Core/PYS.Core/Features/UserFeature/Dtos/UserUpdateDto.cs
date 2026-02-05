using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Dtos
{
    public class UserUpdateDto
    {
        public Guid Id { get; set; }
        public int RegistrationNumber { get; set; }
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public bool IsEntryPlan { get; set; }
        public string Position { get; set; }
        public string Unit { get; set; }
        public string Email { get; set; }
        public bool LockoutEnabled { get; set; }
        public bool IsLDAP { get; set; }
        public string Password { get; set; }

        public List<Guid> SelectedRoles { get; set; }

        public Guid RoleId { get; set; }
        public List<AppRole> Roles { get; set; }
    }
}
