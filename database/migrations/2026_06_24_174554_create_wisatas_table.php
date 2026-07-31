<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->longText('deskripsi')->nullable();
            $table->string('alamat')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('main_route_lat', 10, 7)->nullable();
            $table->decimal('main_route_long', 10, 7)->nullable();
            $table->decimal('alt_route_lat', 10, 7)->nullable();
            $table->decimal('alt_route_long', 10, 7)->nullable();

            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();

            $table->boolean('featured')->default(false);
            $table->boolean('publish')->default(true);
            $table->index(['publish', 'featured']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
