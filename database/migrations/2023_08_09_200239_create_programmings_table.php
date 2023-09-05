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
        Schema::create('programmings', function (Blueprint $table) {
            $table->id();
            $table->integer('quota');
            $table->integer('quota_available')->nullable();
            $table->date('initial_date')->nullable();
            $table->time('initial_time')->nullable();
            $table->date('final_date')->nullable();
            $table->time('final_time')->nullable();
            $table->boolean('state')->default(true);
            $table->boolean('waiting')->default(0);

            $table->unsignedBigInteger('event_id')->default(1);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmings');
    }
};
