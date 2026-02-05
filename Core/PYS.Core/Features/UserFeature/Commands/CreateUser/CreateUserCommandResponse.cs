using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.UserFeature.Commands.CreateUser
{
    public record CreateUserCommandResponse() : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
    }
}
