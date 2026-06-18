# Proyecto ASP.NET Core MVC — MiPrimerMVC

Este proyecto es una aplicación web interactiva desarrollada en **ASP.NET Core MVC** como parte de la práctica de clase (GA) y su correspondiente extensión fuera de clases (TA).

**Autor:** Justyn Keith Cruz Perez

---

## 🚀 Requisitos Previos

Antes de ejecutar la aplicación, asegúrese de tener instalado en su equipo:
* **.NET SDK** versión `8.0` o superior. Puede verificarlo ejecutando:
  ```bash
  dotnet --version
  ```
* Un navegador web moderno (Edge, Chrome, Firefox, etc.).

---

## 🛠️ Instrucciones de Ejecución

1. Abra una terminal o consola de comandos en el directorio del proyecto `MiPrimerMVC` (donde se encuentra el archivo `MiPrimerMVC.csproj`).
2. Ejecute el siguiente comando para restaurar las dependencias, compilar e iniciar el servidor de desarrollo:
   ```bash
   dotnet run --launch-profile "http"
   ```
3. La aplicación se compilará e iniciará un servidor web local. Por defecto, estará escuchando en la siguiente dirección URL:
   * [http://localhost:5087](http://localhost:5087)
4. Abra su navegador web e ingrese a la sección de administración de productos:
   * [http://localhost:5087/Producto](http://localhost:5087/Producto)

*Nota: Si prefiere habilitar la recarga en caliente (Hot Reload) durante el desarrollo, puede ejecutar:*
```bash
dotnet watch
```

---

## ✨ Características y Directrices Implementadas

### 1. Modelo `Producto` con Data Annotations
El modelo de datos `Producto.cs` ubicado en `Models/` incluye validaciones enriquecidas utilizando decoradores (`DataAnnotations`):
* **Id**: Clave primaria auto-incremental.
* **Nombre**: Obligatorio, longitud entre 3 y 100 caracteres.
* **Precio**: Obligatorio, rango válido de $0.01 a $10,000.00.
* **Descripción**: Opcional, longitud máxima de 250 caracteres.
* **Stock**: Obligatorio, valor no negativo hasta un máximo de 1000 unidades.

### 2. Controlador `ProductoController`
Implementa la lógica del flujo de la aplicación con las siguientes acciones en `Controllers/ProductoController.cs`:
* **Index (GET)**: Recupera la lista de productos y la envía a la vista.
* **Create (GET)**: Retorna el formulario de creación limpio.
* **Create (POST)**: Valida el modelo y, si es correcto, agrega el producto al listado en memoria y redirige a la vista `Index`.
* **Delete (GET)**: Recupera el producto específico mediante su `id` y muestra la página de confirmación de borrado.
* **Delete (POST)**: Procesa la eliminación del producto de forma segura y redirige al listado general.

### 3. Vistas Razor y Validación del Lado del Cliente / Servidor
Las vistas en `Views/Producto/` fueron estructuradas con diseño responsivo usando **Bootstrap 5**:
* **`Index.cshtml`**: Muestra una tabla responsiva con estilos alternados, distintivos personalizados de colores según el nivel de stock (Sin stock en rojo, bajo en amarillo, óptimo en celeste), y botones de acción "Eliminar".
* **`Create.cshtml`**: Formulario interactivo de registro que implementa:
  * **Validación en el Servidor**: Utilizando `ModelState.IsValid` en el controlador para detener peticiones maliciosas o incorrectas.
  * **Validación en el Cliente**: Mediante la inclusión de las librerías jQuery Validation y jQuery Unobtrusive Validation (`_ValidationScriptsPartial.cshtml`), previniendo el envío del formulario sin recargar la página si existen errores.
* **`Delete.cshtml`**: Vista dedicada para la confirmación de eliminación con advertencias visuales claras y la visualización de la información del elemento a borrar.

### 4. Seguridad CSRF y Anti-Forgery Tokens
Para prevenir ataques de falsificación de solicitudes en sitios cruzados (Cross-Site Request Forgery - CSRF), se implementó:
* Atributo `[ValidateAntiForgeryToken]` sobre las acciones POST de `Create` y `Delete` en el controlador.
* Inyección explícita del token de validación en los formularios Razor a través del Tag Helper `@Html.AntiForgeryToken()`.
