using AutoMapper;
using MediatR;
using Microsoft.AspNetCore.Identity;
using Microsoft.Extensions.Logging;
using PYS.Core.Application.Features.UserFeature.Dtos;
using PYS.Core.Application.Interfaces.Context;
using SantaFarma.Architecture.Core.Application.Bases;
using SantaFarma.Architecture.Core.Application.Interfaces.UnitOfWorks;
using SantaFarma.Architecture.Core.Domain.Entities;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByEmail;
using PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeLogByEmail;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Features.UserFeature.Queries.GetEmployeeByUserId
{
    public class GetEmployeeByUserIdHandler(
        IUnitOfWork<ISAPDbContext> unitOfWork,
        IMapper mapper,
        ILogger<GetEmployeeByUserIdHandler> logger,
        UserManager<AppUser> userManager,
        IMediator mediator) : BaseHandler<ISAPDbContext, GetEmployeeByUserIdHandler>(unitOfWork, mapper, logger), IRequestHandler<GetEmployeeByUserIdRequest, GetEmployeeByUserIdResponse>
    {
        public async Task<GetEmployeeByUserIdResponse> Handle(GetEmployeeByUserIdRequest request, CancellationToken cancellationToken)
        {
            UserDto userDto = new UserDto();
            AppUser appUser = await userManager.FindByIdAsync(request.Id.ToString());
            if (appUser is not null)
            {
                userDto = mapper.Map<UserDto>(appUser);

                SapEmployeeList sapEmployee = new SapEmployeeList();
                var GetEmployeeByEmailFromSapResponse = await mediator.Send(new GetEmployeeByEmailFromSapRequest { Email = appUser.Email });
                sapEmployee = mapper.Map<SapEmployeeList>(GetEmployeeByEmailFromSapResponse);

                if (sapEmployee is not null)
                {
                    //Aktif Personel
                    userDto.FirstName = sapEmployee.Vorna;
                    userDto.LastName = sapEmployee.Nachn;
                    userDto.Position = sapEmployee.Plstx;
                    userDto.Unit = sapEmployee.Orgtx;
                    userDto.LockoutEnabled = false;
                }
                else
                {
                    SapEmployeeListLog sapEmployeeLog = new SapEmployeeListLog();
                    var getEmployeeLogByEmailFromSapResponse = await mediator.Send(new GetEmployeeLogByEmailFromSapRequest { Email = appUser.Email });
                    sapEmployeeLog = mapper.Map<SapEmployeeListLog>(getEmployeeLogByEmailFromSapResponse);

                    if (sapEmployeeLog is not null)
                    {
                        //Pasif Personel
                        userDto.FirstName = sapEmployeeLog.Vorna;
                        userDto.LastName = sapEmployeeLog.Nachn;
                        userDto.Position = sapEmployeeLog.Plstx;
                        userDto.Unit = sapEmployeeLog.Orgtx;
                        userDto.LockoutEnabled = true;
                    }
                }
            }
            return mapper.Map<GetEmployeeByUserIdResponse>(userDto);
        }
    }

}
