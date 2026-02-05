namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole
{
    public class GetAllUsersWithRoleResponse
    {
        public Guid Id { get; set; }
        public int RegistrationNumber { get; set; }
        public string IdentificationNumber { get; set; }
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public bool IsEntryPlan { get; set; }
        public string Position { get; set; }
        public string Unit { get; set; }
        public string Email { get; set; }
        public bool EmailConfirmed { get; set; }
        public string PhoneNumber { get; set; }
        public int AccessFailedCount { get; set; }
        public IList<string> Role { get; set; }
        public bool LockoutEnabled { get; set; }
        public bool IsLDAP { get; set; }
        public bool IsActive { get; set; }
    }
}
