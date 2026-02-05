using MediatR;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PYS.Core.Application.Features.ProductFeature.Queries.GetProductById
{
    public record GetProductByIdRequest(
        Guid Id
        ) : IRequest<GetProductByIdResponse>;
}
