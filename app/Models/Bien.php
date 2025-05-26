<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'codigo',
        'cantidad',
        'estado',
    ];

    public function prestamos()
    {
    return $this->belongsToMany(Prestamo::class, 'prestamo_bien')
                ->withPivot('cantidad_prestada', 'cantidad_devuelta', 'estado_devolucion')
                ->withTimestamps();
    }

}
