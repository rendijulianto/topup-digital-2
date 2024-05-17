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
        Schema::create('topup', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('produk_id');
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('tipe_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('kasir_id')->nullable();
            $table->string('nomor');
            $table->string('keterangan')->nullable();
            $table->double('harga_jual', 15, 2);
            $table->double('harga_beli', 15, 2);
            $table->string('whatsapp', 15)->nullable();
            $table->enum('status', ['sukses', 'gagal', 'pending'])->default('pending');
            $table->enum('tipe', ['seluler', 'voucher', 'token_listrik', 'e_wallet'])->default('seluler');
            $table->dateTime('tgl_transaksi')->nullable();
            $table->timestamps();
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipe_id')->references('id')->on('tipe')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade')->onUpdate('cascade');    
            $table->foreign('kasir_id')->references('id')->on('pengguna')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE topup MODIFY kategori_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topup MODIFY tipe_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topup MODIFY brand_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topup MODIFY kasir_id INT(3) UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup');
    }
};
