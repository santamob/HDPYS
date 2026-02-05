namespace PYS.Core.Application.Interfaces.Authentication
{
    public interface ILDAPAuthService
    {
        Task<bool> LDAPAuthCheck(string UserName, string Password);
    }
}
