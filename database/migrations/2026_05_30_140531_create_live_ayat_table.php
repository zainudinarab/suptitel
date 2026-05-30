<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Grup subtitle
        Schema::create('subtitle_groups', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });

        // Isi subtitle
        Schema::create('subtitle_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained('subtitle_groups')
                ->cascadeOnDelete();

            $table->string('judul')->nullable();

            $table->longText('isi');

            $table->integer('urutan')->default(0);

            $table->timestamps();
        });

        // Status subtitle yang sedang tayang
        Schema::create('subtitle_live', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->nullable()
                ->constrained('subtitle_groups')
                ->nullOnDelete();

            $table->foreignId('subtitle_item_id')
                ->nullable()
                ->constrained('subtitle_items')
                ->nullOnDelete();

            $table->integer('current_word')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtitle_live');
        Schema::dropIfExists('subtitle_items');
        Schema::dropIfExists('subtitle_groups');
    }
};
