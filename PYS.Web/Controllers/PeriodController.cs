using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SantaFarma.Architecture.Core.Application.Interfaces.RedisCache;
using PYS.Web.Models;
using System.Diagnostics;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Create;
using PYS.Core.Application.Features.PeriodsFeature.Dtos;
using AutoMapper;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetAllPeriods;
using PYS.Core.Domain.Entities;
using PYS.Web.ViewModels.Period;
using PYS.Core.Application.Features.FormTypesFeature.Queries.GetAllFormTypes;
using PYS.Core.Application.Features.FormTypesFeature.Dtos;
using PYS.Core.Application.Features.LocationsFeature.Queries.GetAllLocations;
using PYS.Core.Application.Features.LocationsFeature.Dtos;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Delete
    ;
using NToastNotify;
using PYS.Web.Services;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetPeriodById;
using PYS.Core.Application.Features.PeriodsFeature.Queries.GetEmployeeByStarDate;
using PYS.Core.Application.Features.PeriodInUserFeature.Commands.Create;
using PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetPeriodInUser;
using SantaFarma.Architecture.Infrastructure.Persistence.UnitOfWorks;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using PYS.Core.Application.Features.PeriodInUserFeature.Queries.GetUserInPeriodById;
using PYS.Core.Application.Features.PeriodInUserFeature.Commands.Update;
using PYS.Core.Application.Features.PeriodsFeature.Commands.Update;


namespace PYS.Web.Controllers;
public class PeriodController(IUnitOfWork<IAppDbContext> unitOfWork,ILogger<PeriodController> logger, IMapper mapper, IMediator mediator, IRedisCacheService redisCacheService, IToastNotification toastNotification) : Controller
{
    public async Task<IActionResult> Index()
    {
        
        var periods = await mediator.Send(new GetAllPeriodsRequest());

        //var formTypesResponse = await mediator.Send(new GetAllFormTypesQueryRequest());
        //var formTypes = mapper.Map<List<FormTypesDto>>(formTypesResponse);

        //var locationsResponse = await mediator.Send(new GetAllLocationsRequest());
        //var locations = mapper.Map<List<LocationsDto>>(locationsResponse);

        var formTypes = await mediator.Send(new GetAllFormTypesQueryRequest());

        var locations = await mediator.Send(new GetAllLocationsRequest());

        var viewModel = new PeriodViewModel
        {
            Periods = mapper.Map<List<PeriodDto>>(periods),
            FormTypes = mapper.Map<List<FormTypesDto>>(formTypes),
            Locations = mapper.Map<List<LocationsDto>>(locations)
        };

        return View(viewModel);
    }
    [HttpPost]
    public async Task<IActionResult> Create(CreatePeriodDto dto)
    {
        var request = mapper.Map<CreatePeriodCommandRequest>(dto);
        var result = await mediator.Send(request);

        if (result.IsSuccess)
        {
            toastNotification.AddSuccessToastMessage("Dönem başarıyla eklendi.", new ToastrOptions { Title = "Başarılı" });
            return RedirectToAction("Index");
        }
        else
        {

            toastNotification.AddErrorToastMessage("", new ToastrOptions { Title = "Başarısız" });
            ModelState.AddModelError("", result.Message);
            return View("Index");

        }
    }

    [HttpPost]
    public async Task<IActionResult> UpdatePeriod(UpdatePeriodDto dto)
    {
        var request = mapper.Map<UpdatePeriodCommandRequest>(dto);
        var result = await mediator.Send(request);

        if (result.IsSuccess)
        {
            toastNotification.AddSuccessToastMessage("Dönem başarıyla güncellendi.", new ToastrOptions { Title = "Başarılı" });
            return RedirectToAction("Index");
        }
        else
        {

            toastNotification.AddErrorToastMessage("", new ToastrOptions { Title = "Başarısız" });
            ModelState.AddModelError("", result.Message);
            return View("Index");

        }
    }

    [HttpPost]
    public async Task<IActionResult> DeletePeriod(Guid id)
    {
        var result = await mediator.Send(new DeletePeriodCommandRequest { PeriodId = id });
        if (result.IsSuccess)
        {
            toastNotification.AddSuccessToastMessage("Dönem başarıyla silindi.", new ToastrOptions { Title = "Başarılı" });
            return RedirectToAction("Index");
        }
        else
        {

            toastNotification.AddErrorToastMessage("", new ToastrOptions { Title = "Başarısız" });
            ModelState.AddModelError("", result.Message);
            return View("Index");

        }
    }

    [HttpGet]
    public async Task<IActionResult> GetPeriodById(Guid id)
    {
        var period = await mediator.Send(new GetPeriodByIdRequest { Id = id });
        return Json(period); 
    }

    [HttpGet]
    public async Task<IActionResult> GetEmployeesByStartDate(DateOnly date)
    {
        var employees = await mediator.Send(new GetEmployeeByStartDateRequest
        {
            StartDate = date
        });
        return Ok(employees);
    }

    [HttpPost]
    [Route("Period/AddUsersToPeriod")]
    public async Task<IActionResult> AddUsersToPeriod([FromBody] CreatePeriodInUserRequest request)
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
                toastNotification.AddSuccessToastMessage("Kullanıcılar başarılı eklendi.", new ToastrOptions { Title = "İşlem Başarılı" });
                return Ok(new { message = "Kullanıcılar döeneme başarıyla eklendi." });
            }
            else
            {
                toastNotification.AddErrorToastMessage(result.Message ?? "Kullanıcılar eklenemedi.", new ToastrOptions { Title = "İşlem Başarısız" });
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
    public async Task<IActionResult> CheckUsersForPeriod(Guid periodId)
    {
        var exists = await unitOfWork.GetAppReadRepository<PeriodInUser>()
            .GetAllAsync(x => x.PeriodId == periodId);

        return Json(exists);
    }

    [HttpGet]
    public async Task<IActionResult> TreeViewPeriod(Guid periodId, int periodYear, string periodTerm)
    {
        ViewBag.PeriodId = periodId;
        ViewBag.Periodyear = periodYear;
        ViewBag.PeriodTerm = periodTerm;
        var result = await mediator.Send(new GetPeriodInUserRequest { PeriodId = periodId });
        return View(result);
    }

    [HttpGet]
    public async Task<IActionResult> DataTablePeriod(Guid periodId, int periodYear, string periodTerm)
    {
        ViewBag.PeriodId = periodId;
        ViewBag.Periodyear = periodYear;
        ViewBag.PeriodTerm = periodTerm;

        var result = await mediator.Send(new GetPeriodInUserRequest { PeriodId = periodId });

        return View(result);
        
    }

    [HttpGet]
    public async Task<IActionResult> GetPeriodUserById(Guid Id)
    {
        var result = await mediator.Send(new GetUserInPeriodByIdRequest { Id = Id });

        if (result == null)
            return NotFound();

        return Json(result);
    }

    [HttpPost]
    public async Task<IActionResult> UpdatePeriodInUser([FromBody] UpdateUserInPeriodRequest request)
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
                toastNotification.AddSuccessToastMessage("Kullanıcılar başarılı eklendi.", new ToastrOptions { Title = "İşlem Başarılı" });
                return Ok(new { message = "Kullanıcılar döeneme başarıyla eklendi." });
            }
            else
            {
                toastNotification.AddErrorToastMessage(result.Message ?? "Kullanıcılar eklenemedi.", new ToastrOptions { Title = "İşlem Başarısız" });
                return BadRequest(result.Message ?? "İşlem sırasında bir hata oluştu.");
            }
        }
        catch (Exception ex)
        {
            toastNotification.AddErrorToastMessage("Beklenmeyen bir hata oluştu.", new ToastrOptions { Title = "Sunucu Hatası" });
            return StatusCode(500, "Sunucu tarafında beklenmeyen bir hata oluştu.");
        }
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
