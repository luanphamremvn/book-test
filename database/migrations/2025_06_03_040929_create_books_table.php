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
        Schema::create('books', function (Blueprint $table) {
            // Use custom primary key instead of default id
            $table->bigIncrements('book_id')->comment('Primary key of books table');

            $table->string('name', 255)->comment('Name of the book');
            $table->string('slug', 50)->unique()->comment('Unique slug for the book');
            $table->string('description', 2500)->comment('Book description');
            $table->dateTime('published_at')->comment('Publication date');
            $table->string('author', 50)->comment('Author of the book');
            $table->string('image', 255)->comment('Book cover image path');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Book status: active or inactive');
            $table->timestamps(); // created_at, updated_at
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
