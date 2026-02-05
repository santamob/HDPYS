using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeesLogs
{
    public class GetAllEmployeesLogsFromSapRequest : IRequest<IList<GetAllEmployeesLogsFromSapResponse>>
    {
    }
}
