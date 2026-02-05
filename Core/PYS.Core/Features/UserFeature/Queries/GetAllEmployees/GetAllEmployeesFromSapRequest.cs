using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees
{
    public class GetAllEmployeesFromSapRequest : IRequest<IList<GetAllEmployeesFromSapResponse>>
    {
    }
}
