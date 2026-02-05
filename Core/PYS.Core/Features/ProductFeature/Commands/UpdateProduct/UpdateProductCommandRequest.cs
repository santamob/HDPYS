using MediatR;

namespace PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct
{
    public record UpdateProductCommandRequest(
        Guid Id,
        string Name,
        int Code,
        Guid CategoryId) : IRequest<UpdateProductCommandResponse>;
}
