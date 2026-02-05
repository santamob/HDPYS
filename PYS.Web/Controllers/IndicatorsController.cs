using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;
using AutoMapper;
using PYS.Core.Application.Features.ProductFeature.Dtos;
using PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using static StackExchange.Redis.Role;
using PYS.Core.Application.Features.IndicatorFeature.Commands.Create;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetAllIndicators;
using PYS.Web.ViewModels.Indicator;
using PYS.Core.Domain.Entities;
using PYS.Core.Application.Features.IndicatorFeature.Dtos;
using NToastNotify;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById;

namespace PYS.Web.Controllers;
public class IndicatorsController(ILogger<IndicatorsController> logger, IMapper mapper, IMediator mediator, IRedisCacheService redisCacheService, IToastNotification toastNotification) : Controller
{
    public async Task<IActionResult> Index()
    {
        //var formTypes = await mediator.Send(new GetAllFormTypesQueryRequest());
        //var result = mapper.Map<List<FormTypesDto>>(formTypes);
        //return View(result);

        var formTypes = await mediator.Send(new GetAllFormTypesQueryRequest());
        var indicators = await mediator.Send(new GetAllIndicatorQueryRequest());

        var viewModel = new IndicatorViewModel
        {
            FormTypes = mapper.Map<List<FormTypesDto>>(formTypes),
            Indicator = mapper.Map<List<IndicatorDto>>(indicators)
        };

        return View(viewModel);
    }

    [HttpPost]
    [Route("Indicators/CreateIndicator")]
    public async Task<IActionResult> CreateIndicator([FromBody] CreateIndicatorCommandRequest request)
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
                toastNotification.AddSuccessToastMessage("Gösterge başarıyla eklendi.", new ToastrOptions { Title = "İşlem Başarılı" });
                return Ok(new { message = "Gösterge başarıyla eklendi." });
            }
            else
            {
                toastNotification.AddErrorToastMessage(result.Message ?? "Gösterge eklenemedi.", new ToastrOptions { Title = "İşlem Başarısız" });
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
    public async Task<IActionResult> GetIndicatorById(Guid id)
    {
        var result = await mediator.Send(new GetIndicatorByIdRequest { Id = id });
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
