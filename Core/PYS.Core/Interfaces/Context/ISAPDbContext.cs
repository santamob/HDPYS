using Microsoft.EntityFrameworkCore;
using PYS.Core.Domain.Entities;

namespace PYS.Core.Application.Interfaces.Context
{
    public interface ISAPDbContext
    {
        DbSet<SapEmployeeList> SapEmployeeLists { get; set; }
        DbSet<SapEmployeeListLog> SapEmployeeListLogs { get; set; }
    }
}
