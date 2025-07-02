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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable()->comment('Path gambar fasilitas');
            $table->json('available_days')->comment('Hari tersedia dalam format [1,2,3,4,5] (1=Senin)');
            $table->time('opening_time')->comment('Jam buka harian');
            $table->time('closing_time')->comment('Jam tutup harian');
            $table->integer('max_booking_hours')->default(1)->comment('Maksimal jam peminjaman');
            $table->boolean('is_active')->default(false)->comment('Status aktif fasilitas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
