using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.RejectGoals
{
    /// <summary>
    /// Hedef reddetme komutu.
    /// Hem 1. yönetici hem de 2. üst yönetici tarafından kullanılır.
    /// ApprovalLevel parametresi ile hangi seviyede red yapıldığı belirlenir.
    /// </summary>
    public class RejectGoalsCommandRequest : IRequest<RejectGoalsCommandResponse>
    {
        public Guid PeriodInUserId { get; set; }
        public Guid PeriodId { get; set; }
        public string? ManagerNote { get; set; }
        /// <summary>
        /// 1 = 1. Yönetici reddi, 2 = 2. Üst yönetici reddi
        /// </summary>
        public int ApprovalLevel { get; set; }
    }
}
