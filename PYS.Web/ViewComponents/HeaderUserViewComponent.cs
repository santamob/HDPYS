using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Caching.Memory;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;

namespace PYS.Web.ViewComponents
{
    public class HeaderUserViewComponent : ViewComponent
    {
        private readonly ILogger<HeaderUserViewComponent> logger;
        private readonly UserManager<AppUser> userManager;
        private readonly IMediator mediator;
        private readonly IMemoryCache memoryCache;

        public HeaderUserViewComponent(ILogger<HeaderUserViewComponent> logger, UserManager<AppUser> userManager, IMediator mediator, IMemoryCache memoryCache)
        {
            this.logger = logger;
            this.userManager = userManager;
            this.mediator = mediator;
            this.memoryCache = memoryCache;
        }
        public async Task<IViewComponentResult> InvokeAsync()
        {
            var cacheKey = $"UserInfo_{User.Identity.Name?.ToLower()}";
            if (memoryCache != null && memoryCache.TryGetValue(cacheKey, out UserDto cachedUser))
            {
                return View(cachedUser);
            }
            var userDto = new UserDto
            {
                FirstName = "-",
                LastName = "-",
                Position = "-",
                Unit = "-",
                Role = new List<string> { "-" }
            };

            var loggedInUser = await userManager.GetUserAsync(HttpContext.User);
            if (loggedInUser == null || string.IsNullOrEmpty(loggedInUser.Email))
                return View(userDto);

            var roles = await userManager.GetRolesAsync(loggedInUser) ?? new List<string>();

            var request = new GetEmployeeByEmailFromSapRequest
            {
                Email = loggedInUser.Email
            };

            var employee = await mediator.Send(request);
            if (employee != null)
            {
                userDto.FirstName = employee.Vorna ?? userDto.FirstName;
                userDto.LastName = employee.Nachn ?? userDto.LastName;
                userDto.Position = employee.Plstx ?? userDto.Position;
                userDto.Unit = employee.Orgtx ?? userDto.Unit;
            }

            userDto.Role = roles.Any() ? roles : userDto.Role;
            memoryCache.Set(cacheKey, userDto, new MemoryCacheEntryOptions
            {
                SlidingExpiration = TimeSpan.FromHours(2),
                Priority = CacheItemPriority.Normal
            });

            return View(userDto);
        }
    }
}
