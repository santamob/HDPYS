using MediatR;

namespace PYS.Core.Application.Features.FormTypesFeature.Commands.Create
{
    public record CreateFormTypeCommandRequest(
       string FormTypeName
       ) : IRequest<CreateFormTypeCommandResponse>;
}
