using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory
{
    public record CreateCategoryCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
    }
}
