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
        Schema::create('book_section', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('book_id')
                ->references('id')
                ->on('books')
                ->cascadeOnDelete();
            $table->foreignId('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_section');
    }
};
