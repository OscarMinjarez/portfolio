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
        Schema::create('ari_interactions', function (Blueprint $table) {
            $table->id();
            $table->text('prompt');
            $table->text('response');
            $table->integer('tokens_used');
            $table->string('session_id');
            $table->boolean('is_helpful')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ari_interactions');
    }
};
