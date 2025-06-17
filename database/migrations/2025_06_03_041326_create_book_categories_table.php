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
        Schema::create('book_categories', function (Blueprint $table) {
            // Use custom primary key instead of default id
            $table->bigIncrements('book_category_id')->comment('Primary key of book_categories table');

            $table->bigInteger('book_id')->unsigned()->nullable()->comment('Foreign key to books table');
            $table->foreign('book_id')
                ->references('book_id')->on('books')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->bigInteger('category_id')->unsigned()->nullable()->comment('Foreign key to categories table');
            $table->foreign('category_id')
                ->references('category_id')->on('categories')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};
