using System;
using System.Collections.Generic;

namespace PYS.Infrastructure.Persistence.TempModels;

public partial class AspNetRole
{
    public Guid Id { get; set; }

    public string? Name { get; set; }

    public string? NormalizedName { get; set; }

    public string? ConcurrencyStamp { get; set; }
}
