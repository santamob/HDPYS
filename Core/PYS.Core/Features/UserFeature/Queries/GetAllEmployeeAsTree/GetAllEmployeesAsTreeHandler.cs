using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using Newtonsoft.Json;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;
using static System.Runtime.InteropServices.JavaScript.JSType;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeAsTree
{
    public class GetAllEmployeesAsTreeHandler(
       IUnitOfWork<ISAPDbContext> unitOfWork,
       IMapper mapper,
       ILogger<GetAllEmployeesAsTreeHandler> logger,
       UserManager<AppUser> userManager,
       IMediator mediator
   ) : BaseHandler<ISAPDbContext, GetAllEmployeesAsTreeHandler>(unitOfWork, mapper, logger),
       IRequestHandler<GetAllEmployeesAsTreeRequest, GetAllEmployeesAsTreeResponse>
    {
        public async Task<GetAllEmployeesAsTreeResponse> Handle(GetAllEmployeesAsTreeRequest request, CancellationToken cancellationToken)
        {
            var sapEmployees = await unitOfWork.GetAppReadRepository<SapEmployeeList>().GetAllAsync();

            var employeeDtos = mapper.Map<List<OrgTreeNode>>(sapEmployees);

            var userDict = employeeDtos.ToDictionary(x => x.PerNr);

            List<OrgTreeNode> tree = new();
            foreach (var node in employeeDtos)
            {
                if (node.MPernr.HasValue && userDict.TryGetValue(node.MPernr.Value, out var manager))
                {
                    manager.Children.Add(node);
                }
                else
                {
                    tree.Add(node); // Üstü olmayanlar root node 
                }
            }

            return new GetAllEmployeesAsTreeResponse { Tree = tree };
        }
    }
}
