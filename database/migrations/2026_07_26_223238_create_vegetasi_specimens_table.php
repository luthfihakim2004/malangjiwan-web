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
        Schema::create('vegetasi_specimens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')
                  ->constrained('vegetasi_species')
                  ->cascadeOnDelete();
            $table->string('kode')->unique(); // e.g. MLJ-001 — printed on physical tag + QR
            $table->foreignId('wisata_id')->nullable()->constrained('wisatas')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('image')->nullable(); // photo of this specific tree
            $table->text('catatan')->nullable();  // individual notes (condition, age, etc.)
            $table->boolean('publish')->default(true);
            $table->timestamps();

            $table->index(['wisata_id', 'publish']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetasi_specimens');
    }
};
