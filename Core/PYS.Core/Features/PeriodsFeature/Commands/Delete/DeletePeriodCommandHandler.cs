using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Constants;
using PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Repositories;

namespace PYS.Core.Application.Features.PeriodsFeature.Commands.Delete
{
    public class DeletePeriodCommandHandler(
       IUnitOfWork<IAppDbContext> unitOfWork,
       ILogger<DeletePeriodCommandHandler> logger)
       : IRequestHandler<DeletePeriodCommandRequest, DeletePeriodCommandResponse>
    {
        public async Task<DeletePeriodCommandResponse> Handle(DeletePeriodCommandRequest request, CancellationToken cancellationToken)
        {

            var period = await unitOfWork.GetAppReadRepository<Periods>().GetAsync(p => p.Id == request.PeriodId,
                                                                                            include: q => q.Include(p => p.FormTypesPeriods)
                                                                                                            .Include(p => p.LocationPeriods)
                                                                                                            .Include(p => p.PeriodInUser)
                                                                                                            .Include(p => p.PotentialEvaluation)
                                                                                                                 .ThenInclude(e => e.PotentialEvaluationDetails));

            if (period == null)
            {
                return new DeletePeriodCommandResponse
                {
                    IsSuccess = false,
                    Message = "Dönem bulunamadı."
                };
            }

            await unitOfWork.GetAppWriteRepository<Periods>().HardDeleteAsync(period);
            var result = await unitOfWork.SaveAsync();

            if (result > 0)
            {

                return new DeletePeriodCommandResponse
                {
                    IsSuccess = true,
                    Message = "Dönem başarıyla silindi."
                };
            }
            else
            {
                return new DeletePeriodCommandResponse
                {
                    IsSuccess = false,
                    Message = "Hata."
                };
            }
              

          
        }
    }



}
