using AutoMapper;
using FluentValidation.Results;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Constants;
using PYS.Core.Application.Features.ProductFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.ProductFeature.Commands.CreateProduct
{
    public class CreateProductCommandHandler(IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<CreateProductCommandHandler> logger, CreateProductCommandValidator validator, CreateProductRules createProductRules, IUserContextService userContextService, IRedisCacheService redisCacheService, ILanguageService languageService) : BaseHandler<IAppDbContext, CreateProductCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<CreateProductCommandRequest, CreateProductCommandResponse>
    {
        public async Task<CreateProductCommandResponse> Handle(CreateProductCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }
            var existingProduct = await unitOfWork.GetAppReadRepository<Product>().GetAsync(p => p.Name == request.Name);
            var productDoesNotExistCheck = await createProductRules.ProductShouldNotBeExist(existingProduct);

            if (!productDoesNotExistCheck.IsSuccess)
                return MergeErrors(validationResult, productDoesNotExistCheck.ErrorMessage!);

            var product = mapper.Map<Product>(request);

            product.CreatedIp = userContextService.IpAddress;
            product.AppUserCreatedId = userContextService.UserId;

            await unitOfWork.GetAppWriteRepository<Product>().AddAsync(product);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {
                await redisCacheService.Clear(CacheKeys.GetAllProducts);
                return new CreateProductCommandResponse
                {
                    Success = true
                };
            }
            else
                return MergeErrors(validationResult, languageService.GetKey("GeneralError"));
        }
        private CreateProductCommandResponse MergeErrors(
         ValidationResult validationResult,
         params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new CreateProductCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
    }
}
