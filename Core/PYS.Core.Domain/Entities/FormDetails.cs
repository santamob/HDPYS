using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class FormDetails : EntityBase
    {
        public Guid Id { get; set; }                    
        public Guid FormId { get; set; }                 
        public Guid IndicatorId { get; set; }            
        public int Weight { get; set; }

        public  Forms Form { get; set; }
        public  Indicator Indicator { get; set; }
    }
}
