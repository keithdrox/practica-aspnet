using System.ComponentModel.DataAnnotations;

namespace MiPrimerMVC.Models;

public class Producto
{
    [Key]
    public int Id { get; set; }

    [Required(ErrorMessage = "El nombre del producto es obligatorio.")]
    [StringLength(100, MinimumLength = 3, ErrorMessage = "El nombre debe tener entre 3 y 100 caracteres.")]
    [Display(Name = "Nombre del Producto")]
    public string Nombre { get; set; } = string.Empty;

    [Required(ErrorMessage = "El precio es obligatorio.")]
    [Range(0.01, 10000.00, ErrorMessage = "El precio debe estar entre 0.01 y 10000.00.")]
    [DataType(DataType.Currency)]
    [Display(Name = "Precio")]
    public decimal Precio { get; set; }

    [StringLength(250, ErrorMessage = "La descripción no puede superar los 250 caracteres.")]
    [Display(Name = "Descripción")]
    public string? Descripcion { get; set; }

    [Required(ErrorMessage = "El stock es obligatorio.")]
    [Range(0, 1000, ErrorMessage = "El stock debe ser un valor no negativo (máximo 1000).")]
    [Display(Name = "Cantidad en Stock")]
    public int Stock { get; set; }
}
