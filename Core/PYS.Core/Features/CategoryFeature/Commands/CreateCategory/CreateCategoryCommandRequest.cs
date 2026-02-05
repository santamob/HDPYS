using MediatR;

namespace PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory
{
    public record CreateCategoryCommandRequest(
        string Name
        ) : IRequest<CreateCategoryCommandResponse>;
}
