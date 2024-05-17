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
        Schema::create('pengguna_pin', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('pengguna_id');
            $table->string('pin', 64);
            $table->timestamps();
            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE pengguna_pin MODIFY id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE pengguna_pin MODIFY pengguna_id INT(3) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna_pin');
    }
};
