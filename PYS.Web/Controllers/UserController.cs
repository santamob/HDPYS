using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using NToastNotify;
using PYS.Web.Consts;
using PYS.Web.Services;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Features.UserFeature.Queries.GetAllUserWithRole;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId;
using PYS.Core.Application.Features.UserFeature.Queries.GetUserRolesById;
using PYS.Core.Application.Features.UserFeature.Commands.CreateUser;
using PYS.Core.Application.Features.UserFeature.Commands.UpdateUser;
using PYS.Core.Application.Features.UserFeature.Commands.UpdateUserStatu;

namespace PYS.Web.Controllers
{
    public class UserController(IMediator mediator, RoleManager<AppRole> roleManager, UserManager<AppUser> userManager, LanguageService languageService, IMapper mapper, IToastNotification toastNotification) : Controller
    {

        [Authorize(Roles = $"{RoleConsts.Admin}")]
        public async Task<IActionResult> Index()
        {
            var response = await mediator.Send(new GetAllUsersWithRoleRequest());
            var map = mapper.Map<List<UserDto>>(response);
            return View(map);
        }
        [HttpGet]
        [Authorize(Roles = $"{RoleConsts.Admin}")]

        public async Task<IActionResult> Create()
        {
            var roles = await roleManager.Roles.ToListAsync();
            return View(new UserCreateDto { Roles = roles });
        }
        [HttpPost]
        [Authorize(Roles = $"{RoleConsts.Admin}")]

        public async Task<IActionResult> Create(UserCreateDto userAddDto)
        {
            var request = mapper.Map<CreateUserCommandRequest>(userAddDto);
            var roles = await roleManager.Roles.ToListAsync();

            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("AddUserSuccess").Value, new ToastrOptions { Title = languageService.GetKey("Success").Value });
                return RedirectToAction("Index", "User");
            }
            else
            {
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View(new UserCreateDto { Roles = roles, SelectedRoles = userAddDto.SelectedRoles });
            }
        }
        [HttpGet]
        [Authorize(Roles = $"{RoleConsts.Admin}")]

        public async Task<IActionResult> Update(Guid id)
        {
            var user = await mediator.Send(new GetEmployeeByUserIdRequest { Id = id });
            var roles = await roleManager.Roles.ToListAsync();
            var selectedRoles = await mediator.Send(new GetUserRolesByIdRequest { Id = id });

            var map = mapper.Map<UserUpdateDto>(user);
            map.Roles = roles;
            map.SelectedRoles = selectedRoles;

            return View(map);
        }
        [HttpPost]
        [Authorize(Roles = $"{RoleConsts.Admin}")]

        public async Task<IActionResult> Update(UserUpdateDto userUpdateDto)
        {
            var request = mapper.Map<UpdateUserCommandRequest>(userUpdateDto);
            var roles = await roleManager.Roles.ToListAsync();

            var result = await mediator.Send(request);
            if (result.Success)
            {
                toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess").Value, new ToastrOptions { Title = languageService.GetKey("Success").Value });
                return RedirectToAction("Index", "User");
            }
            else
            {
                foreach (var error in result.Errors)
                    ModelState.AddModelError("", error);
                return View(new UserCreateDto { Roles = roles, SelectedRoles = userUpdateDto.SelectedRoles });
            }
        }
        public async Task<IActionResult> UpdateStatu(Guid id, bool isActive)
        {
            try
            {
                var result = await mediator.Send(new UpdateUserStatuCommandRequest(UserId: id, IsActive: isActive));
                if (result.Success)
                {
                    toastNotification.AddSuccessToastMessage(languageService.GetKey("SuccessProcess").Value, new ToastrOptions { Title = languageService.GetKey("Success").Value });
                }
                else
                {
                    toastNotification.AddErrorToastMessage(languageService.GetKey("ErrorProcess").Value, new ToastrOptions { Title = languageService.GetKey("Error").Value });
                }
                return RedirectToAction("Index", "User");
            }
            catch (Exception)
            {

                throw;
            }
        }
        [HttpPost]
        [Authorize(Roles = $"{RoleConsts.Admin}")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> GetEmployee([FromBody] UserCreateDto query)
        {
            if (string.IsNullOrEmpty(query.Email))
            {
                return Json(new { success = false, title = languageService.GetKey("Warning").Value, message = languageService.GetKey("EmailCannotBeEmpty").Value });
            }
            else
            {
                var employee = await mediator.Send(new GetEmployeeByEmailFromSapRequest { Email = query.Email });
                if (employee != null)
                {
                    return Json(new
                    {
                        success = true,
                        name = employee.Vorna,
                        surname = employee.Nachn,
                        registrationNumber = employee.Pernr,
                        unit = employee.Orgtx,
                        position = employee.Plstx
                    });
                }
                else
                {
                    return Json(new { success = false, title = languageService.GetKey("Warning").Value, message = languageService.GetKey("EmployeeNotFound").Value });
                }
            }
        }
    }
}
