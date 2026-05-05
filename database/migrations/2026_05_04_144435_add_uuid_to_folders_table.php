<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('folders', 'uuid')) {
            Schema::table('folders', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
        }

        DB::table('folders')->whereNull('uuid')->orderBy('id')->each(function ($folder) {
            DB::table('folders')->where('id', $folder->id)->update(['uuid' => Str::uuid()]);
        });
    }

    public function down(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
