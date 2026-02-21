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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255);
            $table->text('doc_number')->nullable();
            $table->date('doc_date')->nullable();
            $table->date('doc_expire_date')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->text('description');
            //FK
            $table->foreignId('user_id')->references('id')->on('users')->nullOnDelete()->nullOnUpdate();
            //constraints 
            $table->unique(['user_id', 'doc_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
