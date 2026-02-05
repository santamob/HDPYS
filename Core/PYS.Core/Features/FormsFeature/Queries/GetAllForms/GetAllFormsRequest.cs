using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.FormsFeature.Commands.Create;

namespace PYS.Core.Application.Features.FormsFeature.Queries.GetAllForms
{
    public class GetAllFormsRequest : IRequest<IList<GetAllFormsResponse>>
    {
    }
}
