<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
       $table->id();
            $table->unsignedBigInteger('folder_id');
            $table->string('original_name', 191);
            $table->string('stored_name', 191)->unique();
            $table->string('mime_type', 191);
            $table->unsignedBigInteger('size');
            $table->string('disk', 191)->default('public');
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->index(['folder_id', 'deleted_at']);
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
