using PYS.Core.Application.Features.CategoryFeature.Dtos;

namespace PYS.Core.Application.Features.ProductFeature.Dtos
{
    public class UpdateProductDto
    {
        public Guid Id { get; set; }
        public string Name { get; set; }
        public int Code { get; set; }
        public Guid CategoryId { get; set; }

        public List<CategoryDto> CategoryDtos { get; set; }
    }
}
