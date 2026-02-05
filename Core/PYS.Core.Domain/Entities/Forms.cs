using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Forms : EntityBase
    {
        public Guid Id { get; set; }                     
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }
        public string FormName { get; set; }
        public int TotalWeight { get; set; }

        public  ICollection<FormDetails> FormDetails { get; set; }

    }
}
