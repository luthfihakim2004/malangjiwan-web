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
        Schema::create('vegetasi_species', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokal');
            $table->string('slug')->unique();
            $table->string('nama_ilmiah')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->text('fun_fact')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('wisata_id')
                  ->nullable()
                  ->constrained('wisatas')
                  ->nullOnDelete();
            $table->boolean('publish')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetasi_species');
    }
};
