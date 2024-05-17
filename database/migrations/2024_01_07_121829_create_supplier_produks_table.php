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
        Schema::create('supplier_produk', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('produk_id')->nullable();
            $table->string('produk_sku_code', 30)->unique();
            $table->double('harga', 15, 2);
            $table->integer('stok');
            $table->boolean('status')->default(false);
            $table->boolean('multi')->default(false);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('supplier')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE supplier_produk MODIFY stok INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_produk');
    }
};
