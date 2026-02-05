using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;

namespace PYS.Web.Controllers;
public class AssignStagingController(ILogger<AssignStagingController> logger, IMediator mediator, IRedisCacheService redisCacheService) : Controller
{
    public async Task<IActionResult> Index()
    {
        return View();
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
