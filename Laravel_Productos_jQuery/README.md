# CRUD de Productos con Laravel + jQuery

Este paquete implementa la actividad del PDF: listar, registrar, editar y eliminar productos mediante AJAX con jQuery.

## Requisitos
- Un proyecto Laravel ya creado (Laravel 10, 11 o 12).
- PHP y Composer instalados.
- Una base de datos configurada en el archivo `.env`.
- Conexión a internet para cargar Bootstrap, jQuery y SweetAlert2 desde CDN.

> Este ZIP contiene el módulo completo para integrar en Laravel; no incluye el framework Laravel ni la carpeta `vendor/`.

## 1. Copiar los archivos
Copia las carpetas `app`, `database`, `resources` y el archivo `routes/web.php` de este paquete dentro de tu proyecto Laravel, respetando las rutas.  
El archivo `routes/web.php` de este ZIP es un ejemplo completo de rutas para esta actividad. Si tu proyecto ya tiene rutas, copia solamente las rutas de productos y los `use` necesarios para no borrar las demás.

## 2. Configurar la base de datos
En el `.env` de tu proyecto, configura `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.

## 3. Ejecutar la migración
Desde la carpeta principal de tu proyecto Laravel:

```bash
php artisan migrate
```

## 4. Iniciar Laravel
```bash
php artisan serve --host=0.0.0.0
```

Abre en el navegador:
`http://127.0.0.1:8000/productos`

## Funciones
- Listado dinámico de productos.
- Registro mediante ventana modal.
- Edición mediante la misma ventana modal.
- Eliminación con confirmación.
- Validación de datos en el servidor.
- Respuestas JSON y actualización de tabla sin recargar la página.
- Protección CSRF en las peticiones AJAX.

## Campos
- Producto: texto obligatorio, máximo 255 caracteres.
- Categoría: texto obligatorio, máximo 255 caracteres.
- Precio: número obligatorio, mínimo 0.
- Stock: entero obligatorio, mínimo 0.

## Estructura incluida
- `app/Models/Producto.php`
- `app/Http/Controllers/ProductoController.php`
- `database/migrations/...create_productos_table.php`
- `resources/views/layouts/principal.blade.php`
- `resources/views/productos/producto.blade.php`
- `resources/views/productos/formulario.blade.php`
- `resources/js/productos.js`
- `routes/web.php`

## Nota
El PDF original muestra fragmentos y capturas, no incluye migración, modelo, layout ni instrucciones de base de datos. Este paquete completa esas piezas para que el CRUD pueda integrarse y funcionar en un proyecto Laravel.
