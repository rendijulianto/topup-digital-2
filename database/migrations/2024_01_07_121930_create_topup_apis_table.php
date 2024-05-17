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
        Schema::create('topup_api', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('topup_id');
            $table->unsignedInteger('supplier_produk_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('pengguna_id');
            $table->string('ref_id', 20)->unique();
            $table->string('trx_id', 50)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->enum('status', ['sukses','gagal','pending'])->default('pending');
            $table->timestamps();
            $table->foreign('topup_id')->references('id')->on('topup')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('supplier')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_produk_id')->references('id')->on('supplier_produk')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE topup_api MODIFY supplier_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topup_api MODIFY pengguna_id INT(3) UNSIGNED NOT NULL');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_api');
    }
};
