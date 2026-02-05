using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.UserFeature.Commands.UpdateUser
{
    public record UpdateUserCommandResponse() : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
    }
}
