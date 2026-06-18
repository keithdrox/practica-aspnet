using Microsoft.EntityFrameworkCore;
using MiPrimerMVC.Models;

namespace MiPrimerMVC.Data;

public class ApplicationDbContext : DbContext
{
    public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
        : base(options)
    {
    }

    public DbSet<Producto> Productos { get; set; } = null!;
}
