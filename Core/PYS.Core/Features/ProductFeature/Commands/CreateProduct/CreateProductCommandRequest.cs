using MediatR;

namespace PYS.Core.Application.Features.ProductFeature.Commands.CreateProduct
{
    public record CreateProductCommandRequest(
        string Name,
        int Code,
        Guid CategoryId
        ) : IRequest<CreateProductCommandResponse>;

}
