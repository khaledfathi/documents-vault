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
        Schema::create('document_category', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //FK
            $table->foreignId('document_id')->references('id')->on('documents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->references('id')->on('categories')->cascadeOnDelete()->cascadeOnUpdate();
            //constraints
            $table->unique(['document_id' , 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_category');
    }
};
