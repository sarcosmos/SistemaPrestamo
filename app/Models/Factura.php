<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'numero_factura',
        'fecha_emision',
        'total',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }
}
