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
        Schema::create('cek_nama', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('ref_id', 20)->unique();
            $table->string('nomor', 15);
            $table->string('nama', 150);
            $table->unsignedInteger('brand_id');
            $table->enum('status', ['sukses', 'gagal', 'pending'])->default('pending');
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_nama');
    }
};
