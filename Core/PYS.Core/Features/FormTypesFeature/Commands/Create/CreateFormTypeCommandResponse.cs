using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.FormTypesFeature.Commands.Create
{
    public record CreateFormTypeCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
    }
}
