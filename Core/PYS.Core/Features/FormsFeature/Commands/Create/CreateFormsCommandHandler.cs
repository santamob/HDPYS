using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.IndicatorFeature.Commands.Create;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.FormsFeature.Commands.Create
{
    public class CreateFormsCommandHandler(
     IUnitOfWork<IAppDbContext> unitOfWork,
     IUserContextService userContextService,
     IMapper mapper,
     ILogger<CreateFormsCommandHandler> logger) : BaseHandler<IAppDbContext, CreateFormsCommandHandler>(unitOfWork, mapper, logger),
     IRequestHandler<CreateFormsCommandRequest, CreateFormsCommandResponse>
    {
        public async Task<CreateFormsCommandResponse> Handle(CreateFormsCommandRequest request, CancellationToken cancellationToken)
        {

            var form = new Forms
            {
                Id = Guid.NewGuid(),
                FormTypeId = request.FormTypeId,
                FormTypeText = request.FormTypeText,
                FormName = request.FormName,
                TotalWeight = request.TotalWeight,
                CreatedDate = DateTime.Now,
                IsActive = true,
                CreatedIp = userContextService.IpAddress,
                AppUserCreatedId = userContextService.UserId
            };

            form.FormDetails = request.FormDetails.Select(detail => new FormDetails
            {
                Id = Guid.NewGuid(),
                FormId = form.Id,
                IndicatorId = detail.IndicatorId,
                Weight = detail.Weight,
                CreatedIp = userContextService.IpAddress,
                AppUserCreatedId = userContextService.UserId,
            }).ToList();

            await unitOfWork.GetAppWriteRepository<Forms>().AddAsync(form);
            await unitOfWork.SaveAsync();


            return new CreateFormsCommandResponse
            {
                IsSuccess = true,
                Message = "Form başarıyla oluşturuldu."
            };
        }
    }
}
