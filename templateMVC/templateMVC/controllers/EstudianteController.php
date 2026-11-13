<?php
require_once 'models/EstudianteModel.php';

class EstudianteController
{
    private EstudianteModel $model;

    public function __construct()
    {
        $this->model = new EstudianteModel();
    }

    public function index(): void
    {
        $tituloPagina = 'Jugadores';
        $jugadores = $this->model->listar();
        $equipos = $this->model->listarEquipos();

        require_once 'views/layout/header.php';
        require_once 'views/estudiante/estudiante.php';
        require_once 'views/layout/footer.php';
    }

    public function editar(int $id): void
    {
        $estudiante = $this->model->obtener($id);
        if (!$estudiante) {
            $this->redirect('/estudiante/index?error=Jugador+no+encontrado');
        }

        $tituloPagina = 'Editar Jugador';
        $equipos = $this->model->listarEquipos();

        require_once 'views/layout/header.php';
        require_once 'views/estudiante/estudiante_editar.php';
        require_once 'views/layout/footer.php';
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/estudiante/index');
        }

        try {
            $foto = $this->subirImagen($_FILES['foto'] ?? null);
            $this->model->guardar([
                'jugador' => $this->postText('jugador'),
                'posicion' => $this->postText('posicion'),
                'dorsal' => $this->postNumber('dorsal'),
                'edad' => $this->postNumber('edad'),
                'club' => $this->postText('club'),
                'equipo_id' => $this->postNumber('equipo_id'),
                'foto' => $foto,
            ]);
            $this->redirect('/estudiante/index?ok=Jugador+registrado+correctamente');
        } catch (Throwable $e) {
            $this->redirect('/estudiante/index?error=' . urlencode($e->getMessage()));
        }
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/estudiante/index');
        }

        try {
            $id = (int)($_POST['idJugador'] ?? 0);
            $actual = $this->model->obtener($id);
            if (!$actual) {
                throw new RuntimeException('Jugador no encontrado.');
            }

            $foto = $actual['foto'] ?? null;
            $nuevaFoto = $this->subirImagen($_FILES['foto'] ?? null);
            if ($nuevaFoto) {
                $this->eliminarArchivoImagen($foto);
                $foto = $nuevaFoto;
            }

            $this->model->actualizar([
                'idJugador' => $id,
                'jugador' => $this->postText('jugador'),
                'posicion' => $this->postText('posicion'),
                'dorsal' => $this->postNumber('dorsal'),
                'edad' => $this->postNumber('edad'),
                'club' => $this->postText('club'),
                'equipo_id' => $this->postNumber('equipo_id'),
                'foto' => $foto,
            ]);
            $this->redirect('/estudiante/index?ok=Jugador+actualizado+correctamente');
        } catch (Throwable $e) {
            $this->redirect('/estudiante/index?error=' . urlencode($e->getMessage()));
        }
    }

    public function eliminar(int $id): void
    {
        try {
            $jugador = $this->model->obtener($id);
            if ($jugador) {
                $this->model->eliminar($id);
                $this->eliminarArchivoImagen($jugador['foto'] ?? null);
            }
            $this->redirect('/estudiante/index?ok=Jugador+eliminado+correctamente');
        } catch (Throwable $e) {
            $this->redirect('/estudiante/index?error=' . urlencode($e->getMessage()));
        }
    }

    private function postText(string $key): string
    {
        $value = trim((string)($_POST[$key] ?? ''));
        if ($value === '') {
            throw new InvalidArgumentException("El campo {$key} es obligatorio.");
        }
        return $value;
    }

    private function postNumber(string $key): ?int
    {
        $value = trim((string)($_POST[$key] ?? ''));
        if ($value === '') {
            return null;
        }
        if (!filter_var($value, FILTER_VALIDATE_INT) && $value !== '0') {
            throw new InvalidArgumentException("El campo {$key} debe ser numérico.");
        }
        return (int)$value;
    }

    private function subirImagen(?array $file): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('No se pudo subir la imagen.');
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new RuntimeException('La imagen no puede superar los 5 MB.');
        }

        $permitidos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset($permitidos[$mime])) {
            throw new RuntimeException('Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.');
        }

        $directorio = __DIR__ . '/../public/uploads/jugadores';
        if (!is_dir($directorio) && !mkdir($directorio, 0775, true)) {
            throw new RuntimeException('No se pudo crear el directorio de imágenes.');
        }

        $nombre = bin2hex(random_bytes(16)) . '.' . $permitidos[$mime];
        $destino = $directorio . '/' . $nombre;
        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new RuntimeException('No se pudo guardar la imagen.');
        }

        return 'uploads/jugadores/' . $nombre;
    }

    private function eliminarArchivoImagen(?string $ruta): void
    {
        if (!$ruta) {
            return;
        }
        $archivo = __DIR__ . '/../public/' . ltrim($ruta, '/');
        if (is_file($archivo)) {
            @unlink($archivo);
        }
    }

    private function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
