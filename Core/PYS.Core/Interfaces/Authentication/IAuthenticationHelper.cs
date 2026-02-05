using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Interfaces.Authentication
{
    public interface IAuthenticationHelper
    {
        Task<GetEmployeeByEmailFromSapResponse> GetSapUserAsync(string email);
        Task AutoRegisterUserAsync(GetEmployeeByEmailFromSapResponse sapUser, string email);
        Task<bool> SignInWithPasswordAsync(AppUser user, UserLoginDto dto);
    }
}
