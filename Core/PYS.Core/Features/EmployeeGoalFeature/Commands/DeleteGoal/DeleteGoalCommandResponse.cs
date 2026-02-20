using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.DeleteGoal
{
    /// <summary>
    /// Hedef silme komutu yanıtı
    /// </summary>
    public class DeleteGoalCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
    }
}
