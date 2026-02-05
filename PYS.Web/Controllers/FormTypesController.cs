using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;
using AutoMapper;
using NToastNotify;
using PYS.Core.Application.Features.FormTypesFeature.Commands.Create;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Web.Services;
using PYS.Core.Application.Interfaces.Localization;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;


namespace PYS.Web.Controllers;
public class FormTypesController(ILogger<FormTypesController> logger, IMapper mapper, IMediator mediator, ILanguageService languageService, IToastNotification toastNotification) : Controller
{

    public async Task<IActionResult> Index()
    {
        var formTypes = await mediator.Send(new GetAllFormTypesQueryRequest());
        var result = mapper.Map<List<FormTypesDto>>(formTypes);
        return View(result);
    }

    public async Task<IActionResult> Create()
    {
        CreateFormTypeDto createFormTypeDto = new CreateFormTypeDto();
        return View(createFormTypeDto);
    }
    [HttpPost]
    public async Task<IActionResult> Create(CreateFormTypeDto createFormTypeDto)
    {
        var request = mapper.Map<CreateFormTypeCommandRequest>(createFormTypeDto);
        var result = await mediator.Send(request);
        if (result.Success)
        {
            toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess"), new ToastrOptions { Title = languageService.GetKey("Success") });
            return RedirectToAction("Index", "FormTypes");
        }
        else
        {
            toastNotification.AddSuccessToastMessage(languageService.GetKey("ErrorProcess"), new ToastrOptions { Title = languageService.GetKey("Error") });
            foreach (var error in result.Errors)
                ModelState.AddModelError("", error);
            return View();
        }
    }

    [AllowAnonymous]
    [Route("Home/Error")]
    public IActionResult Error(string message, int code)
    {
        return View(new ErrorViewModel { ErrorMessage = Activity.Current?.Id ?? HttpContext.TraceIdentifier, StatusCode = code });
    }

}
