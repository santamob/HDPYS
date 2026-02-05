using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct
{
    public record UpdateProductCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
    }
}
