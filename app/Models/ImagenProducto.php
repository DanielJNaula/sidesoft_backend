<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Image;

class ImagenProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'producto_id'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public static function guardarImagen($request)
    {
        $file          = $request->file('imagen');
        $nombre_imagen = time() . '-' . strtr($request->nombre, " ", "_") . '.' . $file->getClientOriginalExtension();
        $path          = 'sidesoft/productos/' . $nombre_imagen;
        $imagen        = Image::make($file->getRealPath())->save($path);
        return '/sidesoft/productos/' . $nombre_imagen;
    }
}
