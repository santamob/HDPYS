using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.FormsFeature.Dtos;

namespace PYS.Core.Application.Features.FormsFeature.Commands.Create
{
    public class CreateFormsCommandRequest : IRequest<CreateFormsCommandResponse>
    {
        public Guid FormTypeId { get; set; }
        public string FormTypeText { get; set; }
        public string FormName { get; set; }
        public int TotalWeight { get; set; }

        public List<FormDetailDto> FormDetails { get; set; }
    }
}
