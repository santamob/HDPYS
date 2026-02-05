using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Domain.Common;

namespace PYS.Core.Domain.Entities
{
    public class Stage : EntityBase
    {
        public Guid IndicatorId { get; set; }          // FK
        public string StageDesc { get; set; }          // Kademe Tanım
        public decimal StageLower { get; set; }        // Kademe Ast
        public decimal StageTop { get; set; }          // Kademe Üst

        // Navigation
        public Indicator Indicator { get; set; }
    }
}
