using System.Text;

namespace PYS.Core.Application.Common.Helpers
{
    public static class EmailNormalizer
    {
        public static string Normalize(string email)
        {
            StringBuilder sb = new StringBuilder();

            foreach (char c in email)
            {
                switch (c)
                {
                    case 'ı':
                        sb.Append('I');
                        break;
                    case 'i':
                        sb.Append('I');
                        break;
                    case 'ş':
                        sb.Append('S');
                        break;
                    case 'ğ':
                        sb.Append('G');
                        break;
                    case 'ü':
                        sb.Append('U');
                        break;
                    case 'ö':
                        sb.Append('O');
                        break;
                    case 'ç':
                        sb.Append('C');
                        break;
                    default:
                        sb.Append(char.ToUpper(c));
                        break;
                }
            }
            return sb.ToString();
        }
    }
}
