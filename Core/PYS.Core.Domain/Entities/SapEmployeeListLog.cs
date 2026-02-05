namespace PYS.Core.Domain.Entities
{
    public partial class SapEmployeeListLog
    {
        public int Id { get; set; }

        public int Pernr { get; set; }

        public string? Vorna { get; set; }

        public string? Nachn { get; set; }

        public string? Ename { get; set; }

        /// <summary>
        /// 1-Beyaz Yaka
        /// 2-Mavi Yaka
        /// 3-Stajyer
        /// </summary>
        public string? Persg { get; set; }

        /// <summary>
        /// 1-BelrszSür.Merkez
        /// 2-BelrszSür.Fabrika
        /// 3-BelrszSür.Saha
        /// 4-BelirliSür.Merkez
        /// 5-BelirliSür.Fabrika
        /// 6-BelirliSür.Saha
        /// 
        /// </summary>
        public string? Persk { get; set; }

        public int? Oid { get; set; }

        public string? Orgtx { get; set; }

        public int? Sid { get; set; }

        public string? Plstx { get; set; }

        public string? UsrId { get; set; }

        public string? Merni { get; set; }

        public string? Mail { get; set; }

        public int? MPernr { get; set; }

        public string? MMail { get; set; }

        public double? Prozt { get; set; }

        public DateOnly? BegDa { get; set; }

        public DateOnly? EndDa { get; set; }

        public DateOnly? Isbas { get; set; }

        public DateOnly? GbDat { get; set; }

        public string? IsTel { get; set; }

        public string? CepTel { get; set; }

        public string? Kademe { get; set; }

        public string? KademeT { get; set; }

        public string? AccAsgmnt { get; set; }

        public string? AccAsgmntT { get; set; }

        public string? PersData { get; set; }

        public int? LogId { get; set; }

        /// <summary>
        /// 1-Yeni, 2-Güncelleme, 3-Silme
        /// </summary>
        public int? Typ { get; set; }

        public string? TypT { get; set; }

        public DateTime? CreatedDate { get; set; }

        public DateTime? UpdatedDate { get; set; }
    }

}
