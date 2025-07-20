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
        Schema::create('book_stock_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('semester_id');
            $table->decimal('purchase_price', 15, 2);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('remaining_quantity');
            $table->unsignedInteger('return_percentage');
            $table->unsignedInteger('max_return_quantity');
            $table->unsignedInteger('remaining_return_quantity');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('semester_id')->references('id')->on('semesters')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_stock_batches');
    }
};
