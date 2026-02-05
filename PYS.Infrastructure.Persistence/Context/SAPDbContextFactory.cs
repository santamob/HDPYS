using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Design;
using Microsoft.Extensions.Configuration;
using SantaFarma.Architecture.Infrastructure.Persistence.Context;

namespace PYS.Infrastructure.Persistence.Context
{
    // Eğer factory kullanmak istiyorsanız:
    public class SAPDbContextFactory : IDesignTimeDbContextFactory<SAPDbContext>
    {
        public SAPDbContext CreateDbContext(string[] args)
        {
            var configuration = new ConfigurationBuilder()
                .SetBasePath(Directory.GetCurrentDirectory())
                .AddJsonFile("appsettings.json")
                .Build();

            var optionsBuilder = new DbContextOptionsBuilder<SAPDbContext>();
            optionsBuilder.UseSqlServer(configuration.GetConnectionString("SF_APPSConnection"));

            Console.WriteLine(configuration.GetConnectionString("SF_APPSConnection"));
            return new SAPDbContext(optionsBuilder.Options);
        }
    }
}