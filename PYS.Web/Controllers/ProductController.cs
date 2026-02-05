using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Mvc;
using NToastNotify;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Application.Features.ProductFeature.Commands.CreateProduct;
using PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct;
using PYS.Core.Application.Features.ProductFeature.Dtos;
using PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct;
using PYS.Core.Application.Features.ProductFeature.Queries.GetProductById;
using PYS.Core.Application.Interfaces.Localization;

namespace PYS.Web.Controllers
{
    public class ProductController(ILogger<ProductController> logger, IMapper mapper, IMediator mediator, ILanguageService languageService, IToastNotification toastNotification) : Controller
    {
        public async Task<IActionResult> Index()
        {
            var products = await mediator.Send(new GetAllProductQueryRequest());
            var productDtos = mapper.Map<List<ProductDto>>(products);
            return View(productDtos);
        }
        [HttpGet]
        public async Task<IActionResult> Create()
        {
            CreateProductDto createProductDto = new CreateProductDto();

            var categories = await mediator.Send(new GetAllCategoryQueryRequest());

            createProductDto.CategoryDtos = mapper.Map<List<CategoryDto>>(categories);
            return View(createProductDto);
        }
        [HttpPost]
        public async Task<IActionResult> Create(CreateProductDto createProductDto)
        {
            var request = mapper.Map<CreateProductCommandRequest>(createProductDto);
            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess"), new ToastrOptions { Title = languageService.GetKey("Success") });
                return RedirectToAction("Index", "Product");
            }
            else
            {
                toastNotification.AddErrorToastMessage(languageService.GetKey("ErrorProcess"), new ToastrOptions { Title = languageService.GetKey("Error") });
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View();
            }
        }

        [HttpGet]
        public async Task<IActionResult> Update(Guid id)
        {
            var existingProduct = await mediator.Send(new GetProductByIdRequest(id));

            var updateProductDto = mapper.Map<UpdateProductDto>(existingProduct);
            var categories = await mediator.Send(new GetAllCategoryQueryRequest());

            updateProductDto.CategoryDtos = mapper.Map<List<CategoryDto>>(categories);

            return View(updateProductDto);
        }
        [HttpPost]
        public async Task<IActionResult> Update(UpdateProductDto updateProductDto)
        {
            var request = mapper.Map<UpdateProductCommandRequest>(updateProductDto);
            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess"), new ToastrOptions { Title = languageService.GetKey("Success") });
                return RedirectToAction("Index", "Product");
            }
            else
            {
                toastNotification.AddErrorToastMessage(languageService.GetKey("ErrorProcess"), new ToastrOptions { Title = languageService.GetKey("Error") });
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View();
            }
        }
    }
}
