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
        Schema::create('campistas_conhece', function (Blueprint $table) {
            $table->foreignId('id_campista')->constrained('campistas')->onDelete('cascade');
            $table->foreignId('id_conhecido')->constrained('campistas')->onDelete('cascade');
            $table->primary(['id_campista', 'id_conhecido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campistas_conhece');
    }
};

