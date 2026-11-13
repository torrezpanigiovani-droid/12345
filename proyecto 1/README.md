# CRUD de productos - Laravel

Proyecto base para integrar en una instalación limpia de Laravel 12 o 13. Incluye listado, registro, edición y eliminación de productos, con búsqueda, validación y diseño adaptable.

## Requisitos
- PHP 8.3 o superior (recomendado para Laravel 13)
- Composer
- Laravel instalado
- SQLite o MySQL

## Instalación recomendada

1. Crea un proyecto Laravel:
   ```bash
   laravel new inventario
   ```
   Puedes elegir el starter kit que prefieras. Para este CRUD no se necesita React, Vue ni Livewire.

2. Copia los archivos de esta carpeta dentro del proyecto Laravel, respetando las rutas:
   - `app/Models/Producto.php`
   - `app/Http/Controllers/ProductoController.php`
   - `database/migrations/2026_01_01_000000_create_productos_table.php`
   - `routes/web.php` (combina la ruta `productos` con las rutas que ya existan; no borres otras rutas necesarias)
   - `resources/views/productos/`
   - `public/css/productos.css`

3. Configura la base de datos en el archivo `.env`.
   Para SQLite, crea el archivo `database/database.sqlite` y configura:
   ```env
   DB_CONNECTION=sqlite
   ```
   Si el proyecto tiene configuraciones DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME y DB_PASSWORD de MySQL, comenta o elimina esas variables si impiden usar SQLite.

4. Ejecuta migraciones:
   ```bash
   php artisan migrate
   ```

5. Inicia el servidor:
   ```bash
   php artisan serve
   ```

6. Abre `http://127.0.0.1:8000/productos`.

## Campos
- Nombre del producto
- Categoría
- Precio
- Stock
- Descripción (opcional)

## Estructura
```text
app/
  Http/Controllers/ProductoController.php
  Models/Producto.php
database/
  migrations/2026_01_01_000000_create_productos_table.php
public/
  css/productos.css
resources/
  views/productos/
    layout.blade.php
    index.blade.php
    create.blade.php
    edit.blade.php
routes/
  web.php
```

## Nota
Este ZIP contiene los archivos fuente del CRUD, no una instalación completa de Laravel ni la carpeta `vendor`. Instalar Laravel con Composer es necesario para ejecutar el proyecto.
