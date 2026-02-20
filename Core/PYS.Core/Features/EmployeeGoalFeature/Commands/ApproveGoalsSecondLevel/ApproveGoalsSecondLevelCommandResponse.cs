using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.ApproveGoalsSecondLevel
{
    /// <summary>
    /// 2. Üst yönetici onay komutu yanıtı
    /// </summary>
    public class ApproveGoalsSecondLevelCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
        public int ApprovedCount { get; set; }
    }
}
