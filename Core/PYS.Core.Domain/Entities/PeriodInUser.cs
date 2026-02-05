using SantaFarma.Architecture.Core.Domain.Common;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Domain.Entities
{
    public class PeriodInUser : EntityBase
    {
        public Guid PeriodId { get; set; }
        public string PeriodText { get; set; }
        public int PerNr { get; set; }              
        public string Ename { get; set; }           
        public string Persg { get; set; }           
        public string Persk { get; set; }           
        public int OID { get; set; }                
        public string Orgtx { get; set; }           
        public int SID { get; set; }                
        public string PLSTX { get; set; }           
        public string Mail { get; set; }            
        public int? MPernr { get; set; }
        //public int? MFullName { get; set; }
        public string? MMail { get; set; }          
        public string AccAsgmnt { get; set; }       
        public string AccAsgmntT { get; set; }

        public int? Level { get; set; } //10 uzm, 20 yönetici, 30 müdür, 40 direktör

        public Periods Period { get; set; }  // Navigasyon
    }

}
