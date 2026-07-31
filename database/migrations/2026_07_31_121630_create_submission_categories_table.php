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
        Schema::create('submission_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('submission_categories')->insert([
            ['nama' => 'Infrastruktur',      'slug' => 'infrastruktur',      'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pelayanan Publik',   'slug' => 'pelayanan-publik',   'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Wisata',             'slug' => 'wisata',             'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'UMKM',               'slug' => 'umkm',               'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lingkungan',         'slug' => 'lingkungan',         'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Keamanan',           'slug' => 'keamanan',           'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lainnya',            'slug' => 'lainnya',            'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_categories');
    }
};
