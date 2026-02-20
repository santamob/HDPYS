using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.UpdateGoal
{
    /// <summary>
    /// Hedef güncelleme komutu yanıtı
    /// </summary>
    public class UpdateGoalCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
    }
}
