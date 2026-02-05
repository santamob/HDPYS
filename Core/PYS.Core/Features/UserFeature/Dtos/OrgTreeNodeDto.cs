using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.UserFeature.Dtos
{
    public class OrgTreeNode
    {
        public int PerNr { get; set; }         
        public int? MPernr { get; set; }       

        public string FullName { get; set; }
        public string Title { get; set; }
        public string Department { get; set; }

        public List<OrgTreeNode> Children { get; set; } = new();
    }
}
