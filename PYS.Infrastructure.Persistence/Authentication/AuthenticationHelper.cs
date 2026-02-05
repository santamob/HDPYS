using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Configuration;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Commands.CreateUser;
using PYS.Core.Application.Interfaces.Authentication;

namespace PYS.Infrastructure.Persistence.Authentication
{
    public class AuthenticationHelper(IConfiguration configuration, IMediator mediator, SignInManager<AppUser> signInManager) : IAuthenticationHelper
    {
        public async Task<GetEmployeeByEmailFromSapResponse> GetSapUserAsync(string email)
        {
            return await mediator.Send(new GetEmployeeByEmailFromSapRequest { Email = email });
        }

        public async Task AutoRegisterUserAsync(GetEmployeeByEmailFromSapResponse sapUser, string email)
        {
            var defaultPassword = configuration.GetValue<string>("ProjectSettings:DefaultPassword") ?? "Sf12345**!";

            var createUserCommandRequest = new CreateUserCommandRequest(
                Email: email,
                Password: defaultPassword,
                SelectedRoles: null,
                IsLDAP: true
            );

            await mediator.Send(createUserCommandRequest);
        }

        public async Task<bool> SignInWithPasswordAsync(AppUser user, UserLoginDto dto)
        {
            var result = await signInManager.PasswordSignInAsync(user, dto.Password, dto.RememberMe, lockoutOnFailure: true);
            return result.Succeeded;
        }
    }
}
