using Microsoft.Extensions.Configuration;
using PYS.Core.Application.Interfaces.Authentication;
using System.DirectoryServices.AccountManagement;
namespace PYS.Infrastructure.Persistence.Authentication
{
    public class LDAPAuthService(IConfiguration configuration) : ILDAPAuthService
    {
        public async Task<bool> LDAPAuthCheck(string userName, string password)
        {
            string ldapServer = configuration.GetValue<string>("LDAPSettings:LDAPServer");
            string ldapUsername = configuration.GetValue<string>("LDAPSettings:LDAPUsername");
            string ldapPassword = configuration.GetValue<string>("LDAPSettings:LDAPPassword");

            using (var pc = new PrincipalContext(ContextType.Domain, ldapServer, ldapUsername, ldapPassword))
            {
                bool isAuthenticated = pc.ValidateCredentials(userName, password);

                return isAuthenticated;
            }
        }
    }
}
