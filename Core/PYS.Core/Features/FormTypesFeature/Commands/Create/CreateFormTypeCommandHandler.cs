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
using PYS.Core.Application.Features.FormTypesFeature.Rules;

namespace PYS.Core.Application.Features.FormTypesFeature.Commands.Create
{
    public class CreateFormTypeCommandHandler(IUserContextService userContextService, IUnitOfWork<IAppDbContext> unitOfWork, IMapper mapper, ILogger<CreateFormTypeCommandHandler> logger, CreateFormTypeCommandValidator validator, CreateFormTypeRules createFormTypeRules, ILanguageService languageService, IRedisCacheService redisCacheService) : BaseHandler<IAppDbContext, CreateFormTypeCommandHandler>(unitOfWork, mapper, logger), IRequestHandler<CreateFormTypeCommandRequest, CreateFormTypeCommandResponse>
    {
        public async Task<CreateFormTypeCommandResponse> Handle(CreateFormTypeCommandRequest request, CancellationToken cancellationToken)
        {
            var validationResult = await validator.ValidateAsync(request);

            if (!validationResult.IsValid)
            {
                return MergeErrors(validationResult, validationResult.Errors.Select(e => e.ErrorMessage).ToArray());
            }
            var existingFormType = await unitOfWork.GetAppReadRepository<FormTypes>().GetAsync(c => c.FormTypeName == request.FormTypeName);
            var FormtypeDoesNotExistCheck = await createFormTypeRules.FormTypeShouldNotBeExist(existingFormType);

            if (!FormtypeDoesNotExistCheck.IsSuccess)
                return MergeErrors(validationResult, FormtypeDoesNotExistCheck.ErrorMessage!);

            var formType = mapper.Map<FormTypes>(request);

            formType.CreatedIp = userContextService.IpAddress;
            formType.AppUserCreatedId = userContextService.UserId;

            await unitOfWork.GetAppWriteRepository<FormTypes>().AddAsync(formType);
            var result = await UnitOfWork.SaveAsync();

            if (result > 0)
            {
                await redisCacheService.Clear(CacheKeys.GetAllFormTypes);
                return new CreateFormTypeCommandResponse
                {
                    Success = true
                };
            }
            else
                return MergeErrors(validationResult, languageService.GetKey("GeneralError"));
        }
        private CreateFormTypeCommandResponse MergeErrors(
          ValidationResult validationResult,
          params string[] errors)
        {
            var errorList = validationResult.Errors
                .Select(e => e.ErrorMessage)
                .Concat(errors)
                .ToList();

            return new CreateFormTypeCommandResponse
            {
                Success = false,
                Errors = errorList
            };
        }
    }
}
