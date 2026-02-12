using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Commands.SubmitForApproval
{
    /// <summary>
    /// Onaya gönderme komutu yanıtı
    /// </summary>
    public class SubmitForApprovalCommandResponse : IBaseResponse
    {
        public bool Success { get; set; }
        public List<string> Errors { get; set; } = new();
        public string? Message { get; set; }
        public int SubmittedCount { get; set; }
    }
}
