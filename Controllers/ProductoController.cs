using Microsoft.AspNetCore.Mvc;
using MiPrimerMVC.Models;

namespace MiPrimerMVC.Controllers;

public class ProductoController : Controller
{
    // Lista estática en memoria para simular una base de datos
    private static readonly List<Producto> _productos = new List<Producto>
    {
        new Producto { Id = 1, Nombre = "Laptop Dell Inspiron", Precio = 749.99m, Descripcion = "Intel i5, 8GB RAM, 256GB SSD", Stock = 15 },
        new Producto { Id = 2, Nombre = "Mouse Gamer RGB", Precio = 29.99m, Descripcion = "Mouse ergonómico con luces led", Stock = 50 },
        new Producto { Id = 3, Nombre = "Teclado Mecánico Redragon", Precio = 59.99m, Descripcion = "Teclado layout español, switches azules", Stock = 30 }
    };

    // GET: /Producto/Index
    public IActionResult Index()
    {
        return View(_productos);
    }

    // GET: /Producto/Create
    public IActionResult Create()
    {
        return View(new Producto());
    }

    // POST: /Producto/Create
    [HttpPost]
    [ValidateAntiForgeryToken]
    public IActionResult Create(Producto producto)
    {
        if (ModelState.IsValid)
        {
            // Asignar un ID auto-incremental simple
            producto.Id = _productos.Any() ? _productos.Max(p => p.Id) + 1 : 1;
            _productos.Add(producto);
            
            // Redireccionar al listado de productos
            return RedirectToAction(nameof(Index));
        }

        // Si el modelo no es válido (validación fallida), regresamos la misma vista
        // con el modelo y los errores detectados por Data Annotations en el Server-Side
        return View(producto);
    }

    // GET: /Producto/Delete/5
    public IActionResult Delete(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var producto = _productos.FirstOrDefault(p => p.Id == id);
        if (producto == null)
        {
            return NotFound();
        }

        return View(producto);
    }

    // POST: /Producto/Delete/5
    [HttpPost, ActionName("Delete")]
    [ValidateAntiForgeryToken]
    public IActionResult DeleteConfirmed(int id)
    {
        var producto = _productos.FirstOrDefault(p => p.Id == id);
        if (producto != null)
        {
            _productos.Remove(producto);
        }
        return RedirectToAction(nameof(Index));
    }
}
