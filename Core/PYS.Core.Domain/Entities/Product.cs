using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Product : EntityBase
    {
        public string Name { get; set; }
        public int Code { get; set; }

        public Guid CategoryId { get; set; }
        public Category Category { get; set; }
    }
}
