# Mundial 26 — Panel de administración

Aplicación web en PHP con panel responsive para gestionar jugadores, selecciones, sedes y partidos. Incluye datos de demostración en `db_mundial2026.sql`.

## Termux (Android)

1. Instala PHP: `pkg update && pkg install php unzip -y`
2. Descomprime esta carpeta donde quieras, por ejemplo dentro de `TEMPLADE`.
3. En Termux entra a la carpeta del proyecto. Debe ser la carpeta que contiene `index.php` y `iniciar_termux.sh`.
4. Ejecuta `bash iniciar_termux.sh`.
5. Abre Chrome en `http://127.0.0.1:8080` y deja Termux abierto.

El script copia el proyecto a `~/Mundial2026_Web` (almacenamiento privado de Termux) para evitar `Cannot create lock - Permission denied (13)` al iniciar el servidor desde el almacenamiento compartido. Para usar otro puerto: `bash iniciar_termux.sh 8081`.

## Base de datos (para guardar/editar datos)

El panel se muestra sin base de datos, pero las funciones de guardar, editar y eliminar necesitan MySQL/MariaDB y el controlador PHP PDO MySQL. Importa `db_mundial2026.sql` en una base llamada `db_mundial2026` y revisa usuario/contraseña al comienzo de `index.php`.

**Aviso:** importar el SQL crea las tablas y datos de demostración y puede reemplazar tablas existentes con esos nombres.

## Comprobación

- PHP: sintaxis comprobada con `php -l` en los archivos PHP.
- ZIP: contenido verificado con `unzip -t`.
- La prueba HTTP de la página debe hacerse en el dispositivo donde se ejecuta Termux. La funcionalidad CRUD no se considera verificada hasta configurar MySQL/MariaDB.
