using System.Diagnostics;
using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using NToastNotify;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployees;
using PYS.Web.Consts;
using PYS.Web.Models;
using PYS.Web.Services;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeWithPerSk;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllEmployeeAsTree;
using Newtonsoft.Json;

namespace PYS.Web.Controllers;
public class OrganizationTreeController(ILogger<OrganizationTreeController> logger, IMediator mediator, IMapper mapper, IRedisCacheService redisCacheService) : Controller
{
    public async Task<IActionResult> Index()
    {
        var response = await mediator.Send(new GetAllEmployeesFromSapRequest());
        var map = mapper.Map<List<SAPUserDto>>(response);
        return View(map);
    }

    [HttpGet]
    public async Task<IActionResult> GetUserDetail(int perNr)
    {
        var result = await mediator.Send(new GetAllEmployeeWithPerSkRequest { PerSk = perNr });

        if (result == null)
            return NotFound();

        return Json(result);
    }

    [HttpGet]
    public async Task<IActionResult> TreeView()
    {
        var result = await mediator.Send(new GetAllEmployeesAsTreeRequest());

        Console.WriteLine(JsonConvert.SerializeObject(result, Formatting.Indented));
        return View(result.Tree);
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
