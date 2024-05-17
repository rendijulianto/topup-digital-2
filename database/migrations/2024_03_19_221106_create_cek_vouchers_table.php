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
        Schema::create('cek_voucher', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('ref_id',20)->unique();
            $table->string('nomor', 50);
            $table->string('keterangan',255);
            $table->enum('status', ['sukses', 'gagal', 'pending'])->default('pending');
            $table->unsignedInteger('brand_id');
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE cek_voucher MODIFY brand_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_voucher');
    }
};
