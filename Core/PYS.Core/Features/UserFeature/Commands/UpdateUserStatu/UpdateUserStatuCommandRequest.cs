using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUserStatu
{
    public record UpdateUserStatuCommandRequest
    (
      Guid UserId,
      bool IsActive
    ) : IRequest<UpdateUserStatuCommandResponse>;
}
