using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.RejectGoals
{
    /// <summary>
    /// Hedef reddetme komutu yanıtı
    /// </summary>
    public class RejectGoalsCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
        public int RejectedCount { get; set; }
    }
}
