using AutoMapper;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.EmployeeGoalFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;

namespace PYS.Core.Application.Features.EmployeeGoalFeature.Queries.GetAvailableIndicators
{
    /// <summary>
    /// Gösterge havuzunu getiren handler.
    /// Aktif göstergeleri çeker ve çalışanın zaten seçtiği göstergeleri işaretler.
    /// </summary>
    public class GetAvailableIndicatorsHandler(
        IUnitOfWork<IAppDbContext> unitOfWork,
        IUserContextService userContextService,
        IMapper mapper,
        ILogger<GetAvailableIndicatorsHandler> logger
    ) : BaseHandler<IAppDbContext, GetAvailableIndicatorsHandler>(unitOfWork, mapper, logger),
        IRequestHandler<GetAvailableIndicatorsRequest, List<AvailableIndicatorDto>>
    {
        public async Task<List<AvailableIndicatorDto>> Handle(GetAvailableIndicatorsRequest request, CancellationToken cancellationToken)
        {
            // Tüm aktif göstergeleri çek
            var indicators = await unitOfWork.GetAppReadRepository<Indicator>()
                .GetAllAsync(i => i.IsActive);

            return indicators.Select(i => new AvailableIndicatorDto
            {
                Id = i.Id,
                IndicatorName = i.IndicatorName,
                IndicatorDesc = i.IndicatorDesc,
                FormTypeText = i.FormTypeText,
                IndicatorCategory = i.IndicatorCategory
            }).OrderBy(i => i.IndicatorName).ToList();
        }
    }
}
