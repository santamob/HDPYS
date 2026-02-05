using AutoMapper;
using PYS.Core.Application.Features.CategoryFeature.Commands.CreateCategory;
using PYS.Core.Application.Features.CategoryFeature.Dtos;
using PYS.Core.Application.Features.CategoryFeature.Queries.GetAllCategory;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Common.Mappings
{
    public class CategoryProfile : Profile
    {
        public CategoryProfile()
        {
            CreateMap<GetAllCategoryQueryResponse, CategoryDto>().ReverseMap();
            CreateMap<GetAllCategoryQueryResponse, Category>().ReverseMap();
            CreateMap<CreateCategoryCommandRequest, CreateCategoryDto>().ReverseMap();
            CreateMap<CreateCategoryCommandRequest, Category>().ReverseMap();
            CreateMap<Category, CategoryDto>().ReverseMap();
        }
    }
}
