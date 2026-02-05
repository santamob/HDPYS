using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Commands.Create;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllEvaluationPeriods;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Web.ViewModels.PotentialEvaluations;
using AutoMapper;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Domain.Entities;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Dtos;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetAllPeriodsForPotentialEvaluations;
using PYS.Core.Application.Features.IndicatorFeature.Queries.GetIndicatorById;
using PYS.Core.Application.Features.PotentialEvaluationFeature.Queries.GetPotantialFormsById;

namespace PYS.Web.Controllers;
public class PotentialFormsController(ILogger<PotentialFormsController> logger, IMapper mapper, IMediator mediator, IRedisCacheService redisCacheService) : Controller
{
    public async Task<IActionResult> Index()
    {
        var evaluationPeriods = await mediator.Send(new GetAllEvaluationPeriodRequest());
        var periods = await mediator.Send(new GetAllPeriodsForPotantialEvaluationRequest());

        var viewModel = new PotentialEvaluationViewModel
        {
            PotentialEvaluationPeriods = mapper.Map<List<PotentialEvaluationPeriodDto>>(evaluationPeriods),
            Periods = mapper.Map<List<PeriodEvaluationDto>>(periods)
        };
        return View(viewModel);
    }

    [HttpGet]
    [Route("PotentialForms/GetEvaluationYears")]
    public async Task<IActionResult> GetEvaluationYears()
    {
        var result = await mediator.Send(new GetAllEvaluationPeriodRequest());
        return Ok(result);
    }

    [HttpPost]
    [Route("PotentialForms/Create")]
    public async Task<IActionResult> Create([FromBody] CreatePotentialEvaluationRequest request)
    {
        if (!ModelState.IsValid)
            return BadRequest("Geçersiz veri.");

        var result = await mediator.Send(request);

        if (result.IsSuccess)
            return Ok(result);

        return BadRequest(result.Message);
    }

    [HttpGet]
    [Route("PotentialForms/GetPotantialFormsById")]
    public async Task<IActionResult> GetPotantialFormsById(Guid PeriodId)
    {
        var result = await mediator.Send(new GetPotentialFormsByIdRequest { PeriodId = PeriodId });
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
