using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.IndicatorFeature.Commands.Create
{
    public class CreateIndicatorCommandResponse 
    {
        public bool IsSuccess { get; set; }
        public string Message { get; set; }
    }
}
