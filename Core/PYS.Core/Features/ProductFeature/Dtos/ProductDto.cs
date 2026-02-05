using PYS.Core.Application.Features.CategoryFeature.Dtos;

namespace PYS.Core.Application.Features.ProductFeature.Dtos
{
    public class ProductDto
    {
        public Guid Id { get; set; }
        public string Name { get; set; }
        public int Code { get; set; }
        public bool IsActive { get; set; }

        public CategoryDto Category { get; set; }
    }
}
