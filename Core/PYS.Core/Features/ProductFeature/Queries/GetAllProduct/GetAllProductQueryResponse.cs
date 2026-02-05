using PYS.Core.Application.Features.CategoryFeature.Dtos;

namespace PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct
{
    public class GetAllProductQueryResponse
    {
        public Guid Id { get; set; }
        public string Name { get; set; }
        public bool IsActive { get; set; }
        public int Code { get; set; }
        public CategoryDto Category { get; set; }
    }
}
