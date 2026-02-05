using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PYS.Core.Application.Interfaces.Localization;
using PYS.Core.Domain.Entities;
using SantaFarma.Architecture.Core.Application.Bases;

namespace PYS.Core.Application.Features.FormTypesFeature.Rules
{
    public class CreateFormTypeRules(ILanguageService languageService) : BaseRules
    {
        public Task<RuleResult> FormTypeShouldNotBeExist(FormTypes? formtypes)
        {
            if (formtypes is not null)
                return Task.FromResult(Failure("HATA!!"));
            return Task.FromResult(Success());
        }
    }
}
