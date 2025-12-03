<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('includes')->nullable()->comment('What is included');
            $table->text('excludes')->nullable()->comment('What is not included');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();

            $table->unique(['product_id', 'locale']);
        });

        // Add fulltext index only for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE product_translations ADD FULLTEXT INDEX product_translations_name_description_fulltext (name, description)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
