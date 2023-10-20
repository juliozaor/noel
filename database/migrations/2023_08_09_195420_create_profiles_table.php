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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('document')->unique();
            $table->string('cell');
            $table->string('address');
            $table->string('neighborhood');
            $table->date('birth');
            $table->string('eps');
            $table->string('reference');
            $table->boolean('is_collaborator')->default(false);
            $table->boolean('experience2022')->default(false);
            $table->boolean('state')->default(true);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
