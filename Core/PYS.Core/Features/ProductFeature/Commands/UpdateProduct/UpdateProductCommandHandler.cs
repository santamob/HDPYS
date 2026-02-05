using AutoMapper;
using FluentValidation.Results;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Constants;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct
{
    public class UpdateProductCommandHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<UpdateProductCommandHandler> logger, UpdateProductCommandValidator validator, IUserContextService userContextService, IRedisCacheService redisCacheService, ILanguageService languageService) : BaseHandler<IAppDbContext, UpdateProductCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<UpdateProductCommandRequest, UpdateProductCommandResponse>
    {
        public async Task<UpdateProductCommandResponse> Handle(UpdateProductCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }
            var existingProduct = await unitOfWork.GetAppReadRepository<Product>().GetAsync(p => p.Id == request.Id);

            if (existingProduct is not null)
            {
                existingProduct.Name = request.Name;
                existingProduct.Code = request.Code;
                existingProduct.CategoryId = request.CategoryId;
                existingProduct.ModifiedDate = DateTime.Now;
                existingProduct.ModifiedIp = userContextService.IpAddress;
                existingProduct.AppUserModifiedId = userContextService.UserId;
            }
            await unitOfWork.GetAppWriteRepository<Product>().UpdateAsync(existingProduct);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {
                await redisCacheService.Clear(CacheKeys.GetAllProducts);
                return new UpdateProductCommandResponse
                {
                    Success = true
                };
            }
            else
                return MergeErrors(validationResult, languageService.GetKey("GeneralError"));
        }
        private UpdateProductCommandResponse MergeErrors(
           ValidationResult validationResult,
           params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new UpdateProductCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
    }
}