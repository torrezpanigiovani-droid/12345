<?php

namespace App\Http\Controllers;

use App\Support\Mensajes;
use Illuminate\Http\Request;

/**
 * Controlador base para los módulos de mantenimiento (usuarios, clientes,
 * vehículos, pisos, sectores, celdas, tarifas).
 * Nunca elimina registros: solo activa / desactiva (columna "estado").
 */
abstract class CrudController extends Controller
{
    protected string $model;
    protected string $titulo;
    protected string $ruta;          // prefijo de las rutas: usuarios, clientes...
    protected array $with = [];
    protected array $withCount = [];

    /** Campos del formulario: name, label, type, options (opcional), col (ancho bootstrap). */
    abstract protected function campos(): array;

    /** Columnas de la tabla: data, label, type (text|ocupada|money). */
    abstract protected function columnas(): array;

    abstract protected function reglas(?int $id): array;

    protected function antesGuardar(array $data, ?int $id): array
    {
        return $data;
    }

    /** Devuelve un mensaje de error si no se puede cambiar el estado. */
    protected function antesCambiarEstado($item): ?string
    {
        return null;
    }

    public function index()
    {
        $campos = collect($this->campos())->map(function ($c) {
            if (isset($c['options'])) {
                $c['options'] = collect($c['options'])
                    ->map(fn ($texto, $valor) => ['v' => (string) $valor, 't' => $texto])
                    ->values()->all();
            }

            return $c;
        })->all();

        return view('crud.index', [
            'titulo' => $this->titulo,
            'ruta' => $this->ruta,
            'campos' => $campos,
            'columnas' => $this->columnas(),
        ]);
    }

    public function data()
    {
        return ($this->model)::with($this->with)
            ->withCount($this->withCount)
            ->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->reglas(null), Mensajes::validacion());
        $data = $this->antesGuardar($data, null);
        ($this->model)::create($data + ['estado' => true]);

        return response()->json(['ok' => true, 'message' => 'Registro creado correctamente.']);
    }

    public function update(Request $request, int $id)
    {
        $item = ($this->model)::findOrFail($id);
        $data = $request->validate($this->reglas($id), Mensajes::validacion());
        $item->update($this->antesGuardar($data, $id));

        return response()->json(['ok' => true, 'message' => 'Registro actualizado correctamente.']);
    }

    /** Activa / desactiva (no se elimina nada). */
    public function toggle(int $id)
    {
        $item = ($this->model)::findOrFail($id);

        if ($error = $this->antesCambiarEstado($item)) {
            return response()->json(['message' => $error], 422);
        }

        $item->estado = ! $item->estado;
        $item->save();

        return response()->json([
            'ok' => true,
            'message' => $item->estado ? 'Registro activado.' : 'Registro desactivado.',
        ]);
    }
}
