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
        Schema::create('campistas_conhece_confidentes', function (Blueprint $table) {
            $table->foreignId('id_campista')->constrained('campistas')->onDelete('cascade');
            $table->foreignId('id_confidente')->constrained('confidentes')->onDelete('cascade');
            $table->primary(['id_campista', 'id_confidente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campistas_conhece_confidentes');
    }
};

