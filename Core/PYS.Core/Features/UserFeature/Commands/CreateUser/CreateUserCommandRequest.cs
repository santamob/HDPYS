using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Commands.CreateUser
{
    public record CreateUserCommandRequest(
     string Email,
     string Password,
     List<Guid>? SelectedRoles,
     bool IsLDAP) : IRequest<CreateUserCommandResponse>;
}
