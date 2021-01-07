<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ImagenProducto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProducto;
use App\Http\Resources\ProductoResource;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductoResource::collection(Producto::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProducto $request)
    {   
        $producto = new Producto;
        $producto->nombre = $request->nombre;
        $producto->save();

        $path_imagen = ImagenProducto::guardarImagen($request);
        $imagen_producto = new ImagenProducto;
        $imagen_producto->path = $path_imagen;
        $imagen_producto->producto_id = $producto->id; 
        $imagen_producto->save();

        return response()->json([
            'res'     => true,
            'message' => 'Producto creado correctamente',
            'data'    => new ProductoResource(Producto::findOrFail($producto->id))

        ], 200);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return new ProductoResource(Producto::findOrFail($producto->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'      => 'required|max:100|unique:productos,nombre,' . $producto->id,

        ], [
            'nombre.required'      => 'El campo nombre es obligatorio',
            'nombre.max'           => 'El campo nombres permite máximo 100 caracteres',
            'nombre.unique'        => 'El nombre debe ser único (ya existe un producto con este nombre)',
        ]);

        $producto->nombre = $request->nombre;
        $producto->save();

        /*if ($request->hasFile('imagen')) {
            $imagen_producto  = ImagenProducto::where('producto_id', $producto->id)->first();
            ImagenProducto::eliminarImagen($imagen_producto);
            $nombre_imagen             = ImagenProducto::guardarImagen($request);
            $imagen_producto->path = $nombre_imagen;
            $imagen_producto->save();
        }*/

        return response()->json([
            'res'     => true,
            'message' => 'Producto actualizado correctamente',
            'data'    => new ProductoResource(Producto::findOrFail($producto->id))
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json([
            'res'     => true,
            'message' => 'Producto eliminado correctamente',

        ], 200);
    }
}
