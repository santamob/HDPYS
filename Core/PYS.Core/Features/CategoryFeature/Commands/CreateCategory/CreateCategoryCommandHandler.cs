using AutoMapper;
using FluentValidation.Results;
using MediatR;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Common.Constants;
using PYS.Core.Application.Features.CategoryFeature.Rules;
using PYS.Core.Application.Interfaces.Context;
using PYS.Core.Application.Interfaces.Localization;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.ContextServices;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory
{
    public class CreateCategoryCommandHandler(IUserContextService userContextService, IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<CreateCategoryCommandHandler> logger, CreateCategoryCommandValidator validator, CreateCategoryRules createCategoryRules, ILanguageService languageService, IRedisCacheService redisCacheService) : BaseHandler<IAppDbContext, CreateCategoryCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<CreateCategoryCommandRequest, CreateCategoryCommandResponse>
    {
        public async Task<CreateCategoryCommandResponse> Handle(CreateCategoryCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }
            var existingCategory = await unitOfWork.GetAppReadRepository<Category>().GetAsync(c => c.Name == request.Name);
            var categoryDoesNotExistCheck = await createCategoryRules.CategoryShouldNotBeExist(existingCategory);

            if (!categoryDoesNotExistCheck.IsSuccess)
                return MergeErrors(validationResult, categoryDoesNotExistCheck.ErrorMessage!);

            var category = mapper.Map<Category>(request);

            category.CreatedIp = userContextService.IpAddress;
            category.AppUserCreatedId = userContextService.UserId;

            await unitOfWork.GetAppWriteRepository<Category>().AddAsync(category);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {
                await redisCacheService.Clear(CacheKeys.GetAllCategories);
                return new CreateCategoryCommandResponse
                {
                    Success = true
                };
            }
            else
                return MergeErrors(validationResult, languageService.GetKey("GeneralError"));
        }
        private CreateCategoryCommandResponse MergeErrors(
          ValidationResult validationResult,
          params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new CreateCategoryCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
    }
}
