using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoals
{
    /// <summary>
    /// 1. Yönetici onay komutu yanıtı
    /// </summary>
    public class ApproveGoalsCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
        public int ApprovedCount { get; set; }
    }
}
