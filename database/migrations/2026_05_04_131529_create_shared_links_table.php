<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_links', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->morphs('shareable'); // shareable_type + shareable_id (File atau Folder)
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('permission', ['view', 'download'])->default('view');
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('access_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_links');
    }
};