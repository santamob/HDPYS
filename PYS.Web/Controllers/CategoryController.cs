using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Mvc;
using NToastNotify;
using PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Web.Controllers
{
    public class CategoryController(ILogger<CategoryController> logger, IMapper mapper, IMediator mediator, ILanguageService languageService, IToastNotification toastNotification) : Controller
    {
        private readonly ILogger<CategoryController> logger = logger;
        private readonly IMapper mapper = mapper;

        public async Task<IActionResult> Index()
        {
            var categories = await mediator.Send(new GetAllCategoryQueryRequest());
            var result = mapper.Map<List<CategoryDto>>(categories);
            return View(result);
        }
        [HttpGet]
        public async Task<IActionResult> Create()
        {
            CreateCategoryDto createCategoryDto = new CreateCategoryDto();
            return View(createCategoryDto);
        }
        [HttpPost]
        public async Task<IActionResult> Create(CreateCategoryDto createCategoryDto)
        {
            var request = mapper.Map<CreateCategoryCommandRequest>(createCategoryDto);
            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess"), new ToastrOptions { Title = languageService.GetKey("Success") });
                return RedirectToAction("Index", "Category");
            }
            else
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("ErrorProcess"), new ToastrOptions { Title = languageService.GetKey("Error") });
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View();
            }
        }
        [HttpGet]
        public async Task<IActionResult> Update(Guid id)
        {
            UpdateCategoryDto updateCategoryDto = new UpdateCategoryDto();
            return View(updateCategoryDto);
        }
        [HttpPost]
        public async Task<IActionResult> Update(CreateCategoryDto createCategoryDto)
        {
            var request = mapper.Map<CreateCategoryCommandRequest>(createCategoryDto);
            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess"), new ToastrOptions { Title = languageService.GetKey("Success") });
                return RedirectToAction("Index", "Category");
            }
            else
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("ErrorProcess"), new ToastrOptions { Title = languageService.GetKey("Error") });
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View();
            }
        }
    }
}
