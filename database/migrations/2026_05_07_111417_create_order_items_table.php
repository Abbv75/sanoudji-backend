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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('quantity');
            $table->integer('unitPrice');
            $table->integer('totalPrice');
            $table->foreignUuid('id_order')->constrained('orders');
            $table->foreignUuid('id_book')->constrained('books');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
