using AutoMapper;
using PYS.Core.Application.Features.ProductFeature.Commands.CreateProduct;
using PYS.Core.Application.Features.ProductFeature.Commands.UpdateProduct;
using PYS.Core.Application.Features.ProductFeature.Dtos;
using PYS.Core.Application.Features.ProductFeature.Queries.GetAllProduct;
using PYS.Core.Application.Features.ProductFeature.Queries.GetProductById;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class ProductProfile : Profile
    {
        public ProductProfile()
        {
            CreateMap<GetAllProductQueryResponse, ProductDto>().ReverseMap();
            CreateMap<GetAllProductQueryResponse, Product>().ReverseMap();
            CreateMap<CreateProductCommandRequest, CreateProductDto>().ReverseMap();
            CreateMap<CreateProductCommandRequest, Product>().ReverseMap();
            CreateMap<UpdateProductCommandRequest, UpdateProductDto>().ReverseMap();
            CreateMap<GetProductByIdResponse, UpdateProductDto>().ReverseMap();
            CreateMap<GetProductByIdResponse, Product>().ReverseMap();

        }
    }
}
