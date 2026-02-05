using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.UserFeature.Dtos;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeAsTree
{
    public class GetAllEmployeesAsTreeRequest : IRequest<GetAllEmployeesAsTreeResponse>
    {
    }
}
