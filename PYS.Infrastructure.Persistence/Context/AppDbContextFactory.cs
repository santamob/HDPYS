using SantaFarma.Architecture.Infrastructure.Persistence.Context;
using Microsoft.EntityFrameworkCore;

namespace PYS.Infrastructure.Persistence.Context
{
    public class AppDbContextFactory : BaseDbContextFactory<AppDbContext>
    {
        protected override AppDbContext CreateContextInstance(DbContextOptions<AppDbContext> options)
        {
            return new AppDbContext(options);
        }
    }
}