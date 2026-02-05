using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUser
{
    public record UpdateUserCommandRequest(
     Guid Id,
     string Password,
     List<Guid>? SelectedRoles,
     bool IsLDAP) : IRequest<UpdateUserCommandResponse>;
}
