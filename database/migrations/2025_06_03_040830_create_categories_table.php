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
        Schema::create('categories', function (Blueprint $table) {
            // Use custom primary key instead of default id
            $table->bigIncrements('category_id')->comment('Primary key of categories table');

            $table->string('name')->comment('Category name');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Category status: active or inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
