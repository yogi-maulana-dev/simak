<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Pelaku (NULL untuk login gagal / sistem)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name', 100)->nullable();
            $table->string('user_email', 150)->nullable();

            // Aksi & deskripsi
            $table->string('action', 60)->index();
            $table->string('description', 255)->nullable();

            // Target polymorphic
            $table->string('subject_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->index(['subject_type', 'subject_id']);

            // Sumber log
            $table->enum('source', ['app', 'trigger', 'system'])->default('app')->index();

            // Konteks request
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('metadata')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->index('created_at');
        });

        // Database trigger (MySQL only) — safety net dari raw SQL / phpMyAdmin
        if (DB::connection()->getDriverName() === 'mysql') {
            $this->createMysqlTriggers();
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            $this->dropMysqlTriggers();
        }
        Schema::dropIfExists('activity_logs');
    }

    private function createMysqlTriggers(): void
    {
        // ── files ──
        DB::unprepared("DROP TRIGGER IF EXISTS trg_files_after_insert");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_files_after_insert AFTER INSERT ON files
        FOR EACH ROW BEGIN
            INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
            VALUES (NEW.uploaded_by,
                    'db_file_create',
                    CONCAT('DB-trigger: file dibuat — ', IFNULL(NEW.original_name, '?')),
                    'App\\\\Models\\\\File', NEW.id, 'trigger', NOW());
        END
        SQL);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_files_after_update");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_files_after_update AFTER UPDATE ON files
        FOR EACH ROW BEGIN
            IF (OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL) THEN
                INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
                VALUES (NEW.uploaded_by,
                        'db_file_softdelete',
                        CONCAT('DB-trigger: file dihapus (soft) — ', IFNULL(NEW.original_name, '?')),
                        'App\\\\Models\\\\File', NEW.id, 'trigger', NOW());
            ELSEIF (OLD.original_name <> NEW.original_name) THEN
                INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
                VALUES (NEW.uploaded_by,
                        'db_file_rename',
                        CONCAT('DB-trigger: file ', OLD.original_name, ' → ', NEW.original_name),
                        'App\\\\Models\\\\File', NEW.id, 'trigger', NOW());
            END IF;
        END
        SQL);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_files_after_delete");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_files_after_delete AFTER DELETE ON files
        FOR EACH ROW BEGIN
            INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
            VALUES (OLD.uploaded_by,
                    'db_file_force_delete',
                    CONCAT('DB-trigger: file dihapus permanen — ', IFNULL(OLD.original_name, '?')),
                    'App\\\\Models\\\\File', OLD.id, 'trigger', NOW());
        END
        SQL);

        // ── folders ──
        DB::unprepared("DROP TRIGGER IF EXISTS trg_folders_after_insert");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_folders_after_insert AFTER INSERT ON folders
        FOR EACH ROW BEGIN
            INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
            VALUES (NEW.created_by,
                    'db_folder_create',
                    CONCAT('DB-trigger: folder dibuat — ', IFNULL(NEW.name, '?')),
                    'App\\\\Models\\\\Folder', NEW.id, 'trigger', NOW());
        END
        SQL);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_folders_after_update");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_folders_after_update AFTER UPDATE ON folders
        FOR EACH ROW BEGIN
            IF (OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL) THEN
                INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
                VALUES (NEW.created_by,
                        'db_folder_softdelete',
                        CONCAT('DB-trigger: folder dihapus (soft) — ', IFNULL(NEW.name, '?')),
                        'App\\\\Models\\\\Folder', NEW.id, 'trigger', NOW());
            ELSEIF (OLD.name <> NEW.name) THEN
                INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
                VALUES (NEW.created_by,
                        'db_folder_rename',
                        CONCAT('DB-trigger: folder ', OLD.name, ' → ', NEW.name),
                        'App\\\\Models\\\\Folder', NEW.id, 'trigger', NOW());
            END IF;
        END
        SQL);

        // ── shared_links ──
        DB::unprepared("DROP TRIGGER IF EXISTS trg_shared_links_after_insert");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_shared_links_after_insert AFTER INSERT ON shared_links
        FOR EACH ROW BEGIN
            INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
            VALUES (NEW.created_by,
                    'db_share_create',
                    CONCAT('DB-trigger: link berbagi dibuat (', NEW.permission, ')'),
                    NEW.shareable_type, NEW.shareable_id, 'trigger', NOW());
        END
        SQL);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_shared_links_after_delete");
        DB::unprepared(<<<SQL
        CREATE TRIGGER trg_shared_links_after_delete AFTER DELETE ON shared_links
        FOR EACH ROW BEGIN
            INSERT INTO activity_logs (user_id, action, description, subject_type, subject_id, source, created_at)
            VALUES (OLD.created_by,
                    'db_share_revoke',
                    'DB-trigger: link berbagi dicabut',
                    OLD.shareable_type, OLD.shareable_id, 'trigger', NOW());
        END
        SQL);
    }

    private function dropMysqlTriggers(): void
    {
        foreach ([
            'trg_files_after_insert', 'trg_files_after_update', 'trg_files_after_delete',
            'trg_folders_after_insert', 'trg_folders_after_update',
            'trg_shared_links_after_insert', 'trg_shared_links_after_delete',
        ] as $t) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$t}");
        }
    }
};
