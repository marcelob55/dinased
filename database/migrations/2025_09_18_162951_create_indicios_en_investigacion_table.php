<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('indicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seguimiento_id')->constrained('seguimiento')->cascadeOnDelete();
            $table->enum('tipo', ['recopilado','para_juicio']);
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('indicios');
    }
};
