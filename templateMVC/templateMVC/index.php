<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
define('BASE_URL', $basePath === '/' ? '' : $basePath);

autoload:
require_once 'controllers/HomeController.php';
require_once 'controllers/EstudianteController.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$route = trim(substr($uri, strlen(BASE_URL)), '/');
$segments = $route === '' ? [] : explode('/', $route);

$controllerName = strtolower($segments[0] ?? 'home');
$action = strtolower($segments[1] ?? 'index');
$id = isset($segments[2]) ? (int)$segments[2] : 0;

try {
    if ($controllerName === 'home' || $controllerName === 'homeindex') {
        (new HomeController())->index();
        exit;
    }

    if ($controllerName === 'estudiante') {
        $controller = new EstudianteController();

        switch ($action) {
            case 'index':
                $controller->index();
                break;
            case 'editar':
                $controller->editar($id);
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'actualizar':
                $controller->actualizar();
                break;
            case 'eliminar':
                $controller->eliminar($id);
                break;
            default:
                http_response_code(404);
                echo 'Ruta no encontrada.';
        }
        exit;
    }

    http_response_code(404);
    echo 'Ruta no encontrada.';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Error</h1><p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
