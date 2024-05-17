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
        Schema::create('website', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('nama', 50);
            $table->string('alamat', 50);
            $table->string('nomor_telepon', 15);
            $table->string('logo_website', 50);
            $table->string('logo_print', 50);
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE website MODIFY id INT(1) UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website');
    }
};
