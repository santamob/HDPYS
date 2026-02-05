using MediatR;


namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk
{
    public class GetAllEmployeeWithPerSkRequest : IRequest<GetAllEmployeeWithPerSkResponse>
    {
        public Guid Id { get; set; }
        public int PerSk { get; set; }
    }
}
