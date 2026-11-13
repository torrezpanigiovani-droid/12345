<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function index()
    {
        return view('productos.producto');
    }

    public function getProduct()
    {
        $productos = Producto::orderBy('idproducto', 'desc')->get();

        return response()->json([
            'success' => true,
            'productos' => $productos,
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'producto' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $producto = Producto::create($datos);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente.',
            'producto' => $producto,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $datos = $request->validate([
            'producto' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($datos);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente.',
            'producto' => $producto->fresh(),
        ]);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente.',
        ]);
    }
}
