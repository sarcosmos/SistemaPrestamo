<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_prestamo',
        'fecha_devolucion',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }


    public function bienes()
    {
        return $this->belongsToMany(Bien::class, 'prestamo_bien')
                    ->withPivot('cantidad_prestada', 'cantidad_devuelta', 'estado_devolucion')
                    ->withTimestamps();
    }
}
