using MediatR;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId
{
    public class GetEmployeeByUserIdRequest : IRequest<GetEmployeeByUserIdResponse>
    {
        public Guid Id { get; set; }

    }
}
