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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('nama', 50);
            $table->string('email', 64)->unique();
            $table->string('password', 64);
            $table->string('token', 64)->nullable();
            $table->boolean('status')->default(true);
            $table->enum('jabatan', ['admin', 'kasir', 'injector']);
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE pengguna MODIFY id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
