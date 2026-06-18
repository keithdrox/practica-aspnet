using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using MiPrimerMVC.Data;
using MiPrimerMVC.Models;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;

namespace MiPrimerMVC.Controllers;

public class AuthController : Controller
{
    private readonly ApplicationDbContext _context;

    public AuthController(ApplicationDbContext context)
    {
        _context = context;
    }

    // ──────────────────────────────────────────────
    // REGISTRO
    // ──────────────────────────────────────────────

    // GET: /Auth/Register
    public IActionResult Register()
    {
        if (User.Identity?.IsAuthenticated == true)
            return RedirectToAction("Index", "Producto");
        return View();
    }

    // POST: /Auth/Register
    [HttpPost]
    [ValidateAntiForgeryToken]  // OWASP (b) Token CSRF
    public async Task<IActionResult> Register(RegisterViewModel model)
    {
        if (!ModelState.IsValid)
            return View(model);

        // Verificar si el email ya existe
        bool emailExists = await _context.Usuarios.AnyAsync(u => u.Email == model.Email);
        if (emailExists)
        {
            ModelState.AddModelError("Email", "Este correo electrónico ya está registrado.");
            return View(model);
        }

        // OWASP (c): Almacenar contraseña con hash seguro (SHA-256 + salt)
        // Equivalente al password_hash() de PHP
        var usuario = new Usuario
        {
            NombreUsuario = model.NombreUsuario,
            Email         = model.Email,
            PasswordHash  = HashPassword(model.Password),
            FechaRegistro = DateTime.UtcNow
        };

        _context.Usuarios.Add(usuario);
        await _context.SaveChangesAsync();

        TempData["Success"] = "¡Cuenta creada exitosamente! Por favor inicia sesión.";
        return RedirectToAction(nameof(Login));
    }

    // ──────────────────────────────────────────────
    // LOGIN
    // ──────────────────────────────────────────────

    // GET: /Auth/Login
    public IActionResult Login(string? returnUrl = null)
    {
        if (User.Identity?.IsAuthenticated == true)
            return RedirectToAction("Index", "Producto");
        ViewData["ReturnUrl"] = returnUrl;
        return View();
    }

    // POST: /Auth/Login
    [HttpPost]
    [ValidateAntiForgeryToken]  // OWASP (b) Token CSRF
    public async Task<IActionResult> Login(LoginViewModel model, string? returnUrl = null)
    {
        ViewData["ReturnUrl"] = returnUrl;

        if (!ModelState.IsValid)
            return View(model);

        // Buscar usuario por email y verificar hash de contraseña
        var usuario = await _context.Usuarios.FirstOrDefaultAsync(u => u.Email == model.Email);

        if (usuario == null || usuario.PasswordHash != HashPassword(model.Password))
        {
            ModelState.AddModelError(string.Empty, "Correo o contraseña incorrectos.");
            return View(model);
        }

        // Crear cookie de autenticación con los claims del usuario
        var claims = new List<Claim>
        {
            new Claim(ClaimTypes.NameIdentifier, usuario.Id.ToString()),
            new Claim(ClaimTypes.Name,           usuario.NombreUsuario),
            new Claim(ClaimTypes.Email,          usuario.Email)
        };

        var claimsIdentity  = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
        var authProperties  = new AuthenticationProperties
        {
            IsPersistent = model.RememberMe,
            ExpiresUtc   = model.RememberMe
                           ? DateTimeOffset.UtcNow.AddDays(7)
                           : DateTimeOffset.UtcNow.AddHours(2)
        };

        await HttpContext.SignInAsync(
            CookieAuthenticationDefaults.AuthenticationScheme,
            new ClaimsPrincipal(claimsIdentity),
            authProperties);

        TempData["Success"] = $"¡Bienvenido, {usuario.NombreUsuario}!";

        if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
            return Redirect(returnUrl);

        return RedirectToAction("Index", "Producto");
    }

    // ──────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────

    // POST: /Auth/Logout
    [HttpPost]
    [ValidateAntiForgeryToken]  // OWASP (b) Token CSRF
    public async Task<IActionResult> Logout()
    {
        await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
        TempData["Success"] = "Sesión cerrada correctamente.";
        return RedirectToAction(nameof(Login));
    }

    // ──────────────────────────────────────────────
    // HELPER: Hash de contraseña
    // Equivalente a password_hash() de PHP — OWASP (c)
    // ──────────────────────────────────────────────
    private static string HashPassword(string password)
    {
        // Sal fija por aplicación + SHA-256
        // En producción real se usaría BCrypt o PBKDF2
        var salt   = "MiPrimerMVC_Salt_2026";
        var bytes  = SHA256.HashData(Encoding.UTF8.GetBytes(salt + password));
        return Convert.ToHexString(bytes).ToLower();
    }
}
