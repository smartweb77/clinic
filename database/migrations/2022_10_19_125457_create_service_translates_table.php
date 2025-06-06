<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_translates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('services')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('short_description');
            $table->string('lang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_translates');
    }
};
