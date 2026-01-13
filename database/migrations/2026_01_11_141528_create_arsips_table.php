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
        Schema::create('arsips', function (Blueprint $table) {
    $table->id();

    $table->string('judul');
    $table->text('deskripsi')->nullable();
    $table->string('file');

    $table->foreignId('fakultas_id')
        ->constrained('fakultas')
        ->cascadeOnDelete();

    $table->foreignId('prodi_id')
        ->nullable()
        ->constrained('prodi')
        ->nullOnDelete();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
