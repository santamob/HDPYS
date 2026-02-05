
namespace PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes
{
    public class GetAllFormTypesQueryResponse
    {

        public Guid Id { get; set; }
        public string FormTypeName { get; set; }
        public bool IsActive { get; set; }
    }
}
