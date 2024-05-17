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
        Schema::create('produk', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('nama', 150);
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('tipe_id');
            $table->double('harga', 15, 2)->default(0);
            $table->string('deskripsi');
            $table->timestamps();
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipe_id')->references('id')->on('tipe')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE produk MODIFY kategori_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE produk MODIFY brand_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE produk MODIFY tipe_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
