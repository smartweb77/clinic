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
        Schema::create('doctor_translates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('doctors')->onDelete('cascade');
            $table->string('full_name');
            $table->string('specialty');
            $table->text('education');
            $table->text('experience');
            $table->string('lang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_translates');
    }
};
