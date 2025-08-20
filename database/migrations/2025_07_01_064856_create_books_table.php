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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('education_level_id');
            $table->unsignedBigInteger('curriculum_id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->decimal('price', 15, 2);
            $table->enum('grade_number', ['1', '2', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'BESAR', 'KECIL']);
            $table->enum('semester', [1, 2]);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['title', 'category_id', 'education_level_id', 'curriculum_id', 'grade_number', 'semester'], 'unique_book_title_combo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
