using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class FormTypes : EntityBase
    {
        public string FormTypeName { get; set; }

       // public ICollection<Periods> Periods { get; set; } = new List<Periods>();

        public ICollection<FormTypesPeriods> FormTypesPeriods { get; set; } = new List<FormTypesPeriods>();

    }
}
