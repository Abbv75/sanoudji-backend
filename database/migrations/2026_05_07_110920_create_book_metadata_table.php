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
        Schema::create('book_metadata', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('value');
            $table->foreignUuid('id_metadata_attribute')->constrained('metadata_attributes');
            $table->foreignUuid('id_book')->constrained('books');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_metadata');
    }
};
