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
        Schema::create('group_permission', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //FK
            $table->foreignId('group_id')->references('id')->on('groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('permission_id')->references('id')->on('permissions')->cascadeOnDelete()->cascadeOnUpdate();
            //constraints 
            $table->unique(['group_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_permission');
    }
};
