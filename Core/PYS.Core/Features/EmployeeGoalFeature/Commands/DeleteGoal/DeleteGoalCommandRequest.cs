using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.DeleteGoal
{
    /// <summary>
    /// Hedef silme komutu.
    /// Sadece Draft durumundaki hedefler silinebilir (soft delete).
    /// </summary>
    public class DeleteGoalCommandRequest : IRequest<DeleteGoalCommandResponse>
    {
        public Guid Id { get; set; }
    }
}
