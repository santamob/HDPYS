using AutoMapper;
using FluentValidation.Results;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.UserFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Commands.CreateUser
{
    public class CreateUserCommandHandler(IUserContextService userContextService, CreateUserRules createUserRules, UserManager<AppUser> userManager, RoleManager<AppRole> roleManager, CreateUserCommandValidator validator, IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<CreateUserCommandHandler> logger) : BaseHandler<IAppDbContext, CreateUserCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<CreateUserCommandRequest, CreateUserCommandResponse>
    {
        public async Task<CreateUserCommandResponse> Handle(CreateUserCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }

            var existingUser = await userManager.FindByEmailAsync(request.Email);
            var userDoesNotExistCheck = await createUserRules.UserShouldNotBeExist(existingUser);
            if (!userDoesNotExistCheck.IsSuccess)
                return MergeErrors(validationResult, userDoesNotExistCheck.ErrorMessage!);

            AppUser user = mapper.Map<AppUser>(request);

            user.UserName = request.Email;
            user.CreatedIp = userContextService.IpAddress;
            user.AppUserCreatedId = null;  //giriş yapabilmek için null olarak güncellendi
            //user.AppUserCreatedId = userContextService.UserId; 

            // IdentityResult result = await userManager.CreateAsync(user, request.Password);

            // 37. satırı şu şekilde güncelleyin:
            IdentityResult result = await userManager.CreateAsync(user, "Sifre123!");
            if (!result.Succeeded)
                return MergeErrors(validationResult, result.Errors.Select(e => e.Description).ToArray());

            if (request.SelectedRoles?.Any() == true)
            {
                await AddRolesAsync(user, request.SelectedRoles);
            }

            return new CreateUserCommandResponse
            {
                Success = true
            };
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
        private CreateUserCommandResponse MergeErrors(
            ValidationResult validationResult,
            params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new CreateUserCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
    }
}
