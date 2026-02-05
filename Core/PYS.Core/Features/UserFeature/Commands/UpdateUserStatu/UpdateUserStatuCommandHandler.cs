using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUserStatu
{
    public class UpdateUserStatuCommandHandler(UserManager<AppUser> userManager, IUserContextService contextService, ILanguageService languageService, IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<UpdateUserStatuCommandHandler> logger) : BaseHandler<IAppDbContext, UpdateUserStatuCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<UpdateUserStatuCommandRequest, UpdateUserStatuCommandResponse>
    {
        public async Task<UpdateUserStatuCommandResponse> Handle(UpdateUserStatuCommandRequest request, CancellationToken cancellationToken)
        {
            var user = await userManager.FindByIdAsync(request.UserId.ToString());

            if (user is not null)
            {
                user.IsActive = request.IsActive;
                user.ModifiedIp = contextService.IpAddress;
                user.ModifiedDate = DateTime.Now;
                user.AppUserModifiedId = contextService.UserId;
                var result = await userManager.UpdateAsync(user);

                return new UpdateUserStatuCommandResponse
                {
                    Success = result.Succeeded,
                    Errors = result.Errors.Select(e => e.Description).ToList()
                };
            }
            else
            {
                return new UpdateUserStatuCommandResponse
                {
                    Success = false,
                    Errors = new List<string> { languageService.GetKey("EmailOrPasswordShouldNotBeInvalid") }
                };
            }
        }
    }
}
