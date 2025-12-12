<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop fulltext index first (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE product_translations DROP INDEX product_translations_name_description_fulltext');
            } catch (\Exception $e) {
                // Index might not exist
            }
        }

        Schema::table('product_translations', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        // Recreate fulltext index with new column name (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE product_translations ADD FULLTEXT INDEX product_translations_title_description_fulltext (title, description)');
        }
    }

    public function down(): void
    {
        // Drop fulltext index first (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE product_translations DROP INDEX product_translations_title_description_fulltext');
            } catch (\Exception $e) {
                // Index might not exist
            }
        }

        Schema::table('product_translations', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });

        // Recreate fulltext index with original column name (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE product_translations ADD FULLTEXT INDEX product_translations_name_description_fulltext (name, description)');
        }
    }
};