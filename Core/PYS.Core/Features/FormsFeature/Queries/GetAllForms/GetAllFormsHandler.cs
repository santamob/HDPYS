using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.FormsFeature.Queries.GetAllForms
{
    public class GetAllFormsHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<GetAllFormsHandler> logger) :
        BaseHandler<IAppDbContext, GetAllFormsHandler>(unitOfWork, mapper, logger), IRequestHandler<GetAllFormsRequest, IList<GetAllFormsResponse>>
    {
        public async Task<IList<GetAllFormsResponse>> Handle(GetAllFormsRequest request, CancellationToken cancellationToken)
        {
            var allForms = await unitOfWork.GetAppReadRepository<Forms>().GetAllAsync(include: x => x.Include(p => p.FormDetails).ThenInclude(fd => fd.Indicator));

            return mapper.Map<List<GetAllFormsResponse>>(allForms);
        }
    }
}
