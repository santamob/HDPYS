using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MediatR;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;

namespace PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators
{
    public class GetAllIndicatorQueryRequest : IRequest<IList<GetAllIndicatorQueryResponse>>
    {
    }
}
