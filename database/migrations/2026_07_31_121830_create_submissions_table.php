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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Tracking
            $table->string('tracking_code', 20)->unique();  // ASP-2026-0042
            $table->string('tracking_pin_hash');             // bcrypt of 4-digit PIN

            // Classification
            $table->string('type');           // pengaduan|laporan|kritik|saran
            $table->string('title');
            $table->longText('description');
            $table->string('status')->default('diterima');
            $table->string('priority')->default('normal'); // low|normal|high|urgent
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('submission_categories')
                  ->nullOnDelete();

            // Polymorphic recipient (wisata|umkm|profil)
            $table->string('recipient_type')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->index(['recipient_type', 'recipient_id']);

            // Reporter
            $table->string('identity_mode')->default('anonymous'); // anonymous|identified
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone')->nullable();
            $table->string('reporter_email')->nullable();

            // Location (optional)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_description')->nullable();

            // Incident
            $table->date('incident_date')->nullable();

            // Attachment
            $table->string('attachment')->nullable(); // storage path

            // Admin note shown to public on tracking page
            $table->text('public_note')->nullable();

            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('tracking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
