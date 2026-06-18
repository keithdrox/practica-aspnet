using System.ComponentModel.DataAnnotations;

namespace MiPrimerMVC.Models;

public class Usuario
{
    [Key]
    public int Id { get; set; }

    [Required]
    [StringLength(50)]
    public string NombreUsuario { get; set; } = string.Empty;

    [Required]
    [StringLength(100)]
    public string Email { get; set; } = string.Empty;

    // Almacenado con BCrypt (equivalente a password_hash de PHP) — OWASP (c)
    [Required]
    public string PasswordHash { get; set; } = string.Empty;

    public DateTime FechaRegistro { get; set; } = DateTime.UtcNow;
}
