using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetUserRolesById
{
    public class GetUserRolesByIdRequest : IRequest<List<Guid>>
    {
        public Guid Id { get; set; }
    }
}
