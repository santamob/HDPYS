namespace PYS.Core.Application.Features.ProductFeature.Queries.GetProductById
{
    public class GetProductByIdResponse
    {
        public Guid Id { get; set; }
        public string Name { get; set; }
        public bool IsActive { get; set; }
        public int Code { get; set; }
        public Guid CategoryId { get; set; }
    }
}
