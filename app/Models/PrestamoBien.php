<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrestamoBien extends Model
{
    use HasFactory;

    protected $table = 'prestamo_bien';

    protected $fillable = [
        'prestamo_id',
        'bien_id',
        'cantidad_prestada',
        'cantidad_devuelta',
        'estado_devolucion',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
