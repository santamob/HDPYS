using AutoMapper;
using FluentValidation.Results;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUser
{
    public class UpdateUserCommandHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, UpdateUserCommandValidator validator, IUserContextService userContextService, UserManager<AppUser> userManager, RoleManager<AppRole> roleManager,
ILanguageService languageService, ILogger<UpdateUserCommandHandler> logger) : BaseHandler<IAppDbContext, UpdateUserCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<UpdateUserCommandRequest, UpdateUserCommandResponse>
    {
        public async Task<UpdateUserCommandResponse> Handle(UpdateUserCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }
            var existingUser = await userManager.FindByIdAsync(request.Id.ToString());
            if (existingUser is not null)
            {
                existingUser.ModifiedDate = DateTime.Now;
                existingUser.ModifiedIp = userContextService.IpAddress;
                existingUser.AppUserModifiedId = userContextService.UserId;
                existingUser.IsLDAP = request.IsLDAP;
                if (!string.IsNullOrWhiteSpace(request.Password))
                {
                    await userManager.RemovePasswordAsync(existingUser);
                    await userManager.AddPasswordAsync(existingUser, request.Password);
                }
                var result = await userManager.UpdateAsync(existingUser);
                if (result.Succeeded)
                {
                    await RemoveAllRolesFromUserAsync(existingUser);
                    if (request.SelectedRoles?.Any() == true)
                    {
                        await AddRolesAsync(existingUser, request.SelectedRoles);
                    }
                }
                else
                {
                    return MergeErrors(validationResult, result.Errors.Select(e => e.Description).ToArray());
                }
                return new UpdateUserCommandResponse
                {
                    Success = true
                };
            }
            else
                return MergeErrors(validationResult, languageService.GetKey("EmailOrPasswordShouldNotBeInvalid"));


        }
        private UpdateUserCommandResponse MergeErrors(
           ValidationResult validationResult,
           params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new UpdateUserCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
        private async Task<IdentityResult> RemoveAllRolesFromUserAsync(AppUser appUser)
        {
            var userRoles = await userManager.GetRolesAsync(appUser);

            foreach (var role in userRoles)
            {
                await userManager.RemoveFromRoleAsync(appUser, role);
            }

            return await userManager.UpdateAsync(appUser);
        }
        private async Task AddRolesAsync(AppUser user, IEnumerable<Guid> roleIds)
        {
            foreach (var roleId in roleIds)
            {
                var role = await roleManager.FindByIdAsync(roleId.ToString());
                if (role != null && !await userManager.IsInRoleAsync(user, role.Name))
                {
                    await userManager.AddToRoleAsync(user, role.Name);
                }
            }
        }
    }
}
