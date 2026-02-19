using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoals
{
    /// <summary>
    /// 1. Yönetici onay komutu.
    /// Belirtilen çalışanın PendingFirstApproval durumundaki hedeflerini onaylar.
    /// </summary>
    public class ApproveGoalsCommandRequest : IRequest<ApproveGoalsCommandResponse>
    {
        public Guid PeriodInUserId { get; set; }
        public Guid PeriodId { get; set; }
        public string? ManagerNote { get; set; }
    }
}
