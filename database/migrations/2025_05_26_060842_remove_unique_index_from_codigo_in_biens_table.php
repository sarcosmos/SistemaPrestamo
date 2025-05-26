<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biens', function (Blueprint $table) {
            $table->dropUnique(['codigo']);
        });
    }

    public function down(): void
    {
        Schema::table('biens', function (Blueprint $table) {
            $table->unique('codigo');
        });
    }
};

