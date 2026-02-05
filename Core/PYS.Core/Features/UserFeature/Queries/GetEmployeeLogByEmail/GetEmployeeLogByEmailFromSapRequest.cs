using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail
{
    public class GetEmployeeLogByEmailFromSapRequest : IRequest<GetEmployeeLogByEmailFromSapResponse>
    {
        public string Email { get; set; }
    }
}
