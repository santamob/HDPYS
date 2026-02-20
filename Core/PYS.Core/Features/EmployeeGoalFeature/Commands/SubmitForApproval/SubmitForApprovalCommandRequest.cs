using MediatR;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.SubmitForApproval
{
    /// <summary>
    /// Hedefleri yönetici onayına gönderme komutu.
    /// Belirtilen dönemdeki tüm Draft/Rejected hedefleri PendingApproval durumuna alır.
    /// </summary>
    public class SubmitForApprovalCommandRequest : IRequest<SubmitForApprovalCommandResponse>
    {
        /// <summary>
        /// Onaya gönderilecek dönem
        /// </summary>
        public Guid PeriodId { get; set; }
    }
}
