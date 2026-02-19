using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoalsSecondLevel
{
    /// <summary>
    /// 2. Üst yönetici onay komutu.
    /// Belirtilen çalışanın PendingSecondApproval durumundaki hedeflerini onaylar.
    /// </summary>
    public class ApproveGoalsSecondLevelCommandRequest : IRequest<ApproveGoalsSecondLevelCommandResponse>
    {
        public Guid PeriodInUserId { get; set; }
        public Guid PeriodId { get; set; }
        public string? ManagerNote { get; set; }
    }
}
