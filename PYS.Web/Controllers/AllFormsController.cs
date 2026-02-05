using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;
using AutoMapper;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using NToastNotify;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators;
using PYS.Web.ViewModels.Indicator;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorsByFormType;
using PYS.Core.Application.Features.IndicatorFeature.Commands.Create;
using PYS.Core.Application.Features.FormsFeature.Commands.Create;
using PYS.Core.Application.Features.FormsFeature.Dtos;
using PYS.Web.ViewModels.AllForms;
using PYS.Core.Application.Features.FormsFeature.Queries.GetAllForms;

namespace PYS.Web.Controllers;
public class AllFormsController(ILogger<AllFormsController> logger, IMapper mapper, IMediator mediator, IRedisCacheService redisCacheService, IToastNotification toastNotification) : Controller
{
    public async Task<IActionResult> Index()
    {
        var formTypes = await mediator.Send(new GetAllFormTypesQueryRequest());
        var indicators = await mediator.Send(new GetAllIndicatorQueryRequest());
        var allForms = await mediator.Send(new GetAllFormsRequest());

        var viewModel = new AllFormsViewModel
        {
            FormTypes = mapper.Map<List<FormTypesDto>>(formTypes),
            Indicator = mapper.Map<List<IndicatorDto>>(indicators),
            Forms= mapper.Map<List<FormDto>>(allForms),
        };

        return View(viewModel);
    }

    [HttpGet]
    public async Task<IActionResult> GetIndicatorsByFormType(Guid formTypeId)
    {
        
        if (formTypeId == Guid.Empty)
            return BadRequest("Form Tipi geçersiz.");

        var result = await mediator.Send(new GetIndicatorsByFormTypeRequest
        {
            FormTypeId = formTypeId
        });

        return Ok(result);
    }

    [HttpPost]
    [Route("AllForms/CreateForm")]
    public async Task<IActionResult> CreateForm([FromBody] CreateFormsCommandRequest request)
    {
        if (!ModelState.IsValid)
        {
            toastNotification.AddErrorToastMessage("Lütfen gerekli alanları doldurunuz.", new ToastrOptions { Title = "Doğrulama Hatası" });
            return BadRequest("Model doğrulaması geçersiz.");
        }

        try
        {
            var result = await mediator.Send(request);

            if (result.IsSuccess)
            {
                toastNotification.AddSuccessToastMessage("Form başarıyla eklendi.", new ToastrOptions { Title = "İşlem Başarılı" });
                return Ok(new { message = "Form başarıyla eklendi." });
            }
            else
            {
                toastNotification.AddErrorToastMessage(result.Message ?? "Form eklenemedi.", new ToastrOptions { Title = "İşlem Başarısız" });
                return BadRequest(result.Message ?? "İşlem sırasında bir hata oluştu.");
            }
        }
        catch (Exception ex)
        {
            toastNotification.AddErrorToastMessage("Beklenmeyen bir hata oluştu.", new ToastrOptions { Title = "Sunucu Hatası" });
            return StatusCode(500, "Sunucu tarafında beklenmeyen bir hata oluştu.");
        }
    }

    [HttpGet]
    public async Task<IActionResult> GetIndicatorsByFormType2(Guid formTypeId)
    {
        if (formTypeId == Guid.Empty)
            return BadRequest("Form Tipi geçersiz.");

        var result = await mediator.Send(new GetIndicatorsByFormTypeRequest
        {
            FormTypeId = formTypeId
        });

        return Ok(result);
    }

    [HttpGet]
    public async Task<IActionResult> GetIndicatorsByFormType3(Guid formTypeId)
    {
        var result = await mediator.Send(new GetIndicatorsByFormTypeRequest{FormTypeId = formTypeId});
        return Json(result);
    }

    public IActionResult Privacy()
    {
        return View();
    }

    [AllowAnonymous]
    [Route("Home/Error")]
    public IActionResult Error(string message, int code)
    {
        return View(new ErrorViewModel { ErrorMessage = Activity.Current?.Id ?? HttpContext.TraceIdentifier, StatusCode = code });
    }

}
