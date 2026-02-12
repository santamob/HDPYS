using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.CreateGoal
{
    /// <summary>
    /// Hedef oluşturma komutu yanıtı
    /// </summary>
    public class CreateGoalCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
        public List<Guid> CreatedGoalIds { get; set; } = new();
    }
}
