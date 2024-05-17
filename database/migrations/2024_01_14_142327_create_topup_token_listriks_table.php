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
        Schema::create('topup_token_listrik', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('topup_id');
            $table->string('nama_pelanggan', 50);
            $table->string('id_pelanggan', 20);
            $table->string('nomor_meter', 20);
            $table->string('segment_power', 20);
            $table->foreign('topup_id')->references('id')->on('topup')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_token_listrik');
    }
};
