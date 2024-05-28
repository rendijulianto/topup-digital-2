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
        Schema::create('user_pin', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('user_id');
            $table->string('pin', 64);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE user_pin MODIFY id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE user_pin MODIFY user_id INT(3) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pin');
    }
};
