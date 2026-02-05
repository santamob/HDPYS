using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail
{
    public class GetEmployeeByEmailFromSapRequest : IRequest<GetEmployeeByEmailFromSapResponse>
    {
        public string Email { get; set; }
    }
}
