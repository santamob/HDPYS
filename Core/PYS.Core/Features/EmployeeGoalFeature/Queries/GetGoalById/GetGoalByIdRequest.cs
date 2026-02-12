using MediatR;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetGoalById
{
    /// <summary>
    /// Tek bir hedefin detayını getirir
    /// </summary>
    public class GetGoalByIdRequest : IRequest<EmployeeGoalDto?>
    {
        public Guid Id { get; set; }
    }
}
