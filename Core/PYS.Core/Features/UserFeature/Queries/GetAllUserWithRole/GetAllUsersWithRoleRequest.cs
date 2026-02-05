using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole
{
    public class GetAllUsersWithRoleRequest : IRequest<IList<GetAllUsersWithRoleResponse>>
    {
    }
}
