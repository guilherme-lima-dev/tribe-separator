<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campistas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->char('genero', 1); // 'm' ou 'f'
            $table->decimal('peso', 5, 2); // peso em kg
            $table->decimal('altura', 5, 2); // altura em cm
            // tribo_id será adicionado na migration create_tribo_table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campistas');
    }
};

