using PYS.Core.Application.Features.PeriodInUserFeature.Dtos;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetPeriodInUser
{
    public class GetPeriodInUserResponse
    {
        public int PerNr { get; set; }
        public string Ename { get; set; }
        public int? MPernr { get; set; }
        public string? MMail { get; set; }
        public int OID { get; set; }
        public int SID { get; set; }
        public string Orgtx { get; set; }
        public string PLSTX { get; set; }
        public string Mail { get; set; }
        public string AccAsgmnt { get; set; }
        public string AccAsgmntT { get; set; }
        public int? Level { get; set; }
        public string Persg { get; set; }
        public string Persk { get; set; }
        public string? MFullName { get; set; }
    }
}
