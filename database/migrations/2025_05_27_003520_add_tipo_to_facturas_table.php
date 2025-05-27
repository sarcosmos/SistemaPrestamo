<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('facturas', function (Blueprint $table) {
        $table->string('tipo')->default('prestamo'); // 'prestamo' o 'devolucion'
    });
}

public function down()
{
    Schema::table('facturas', function (Blueprint $table) {
        $table->dropColumn('tipo');
    });
}

};
