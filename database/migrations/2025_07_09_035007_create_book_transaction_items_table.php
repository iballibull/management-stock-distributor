<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_stock_batch_id')->nullable();
            $table->unsignedBigInteger('book_transaction_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();

            $table->foreign('book_stock_batch_id')->references('id')->on('book_stock_batches')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('book_transaction_id')->references('id')->on('book_stock_batches')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('book_id')->references('id')->on('books')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_transaction_items');
    }
};
