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
        Schema::create('topup_e_wallet', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('topup_id');
            $table->string('nama_pelanggan');	
            $table->foreign('topup_id')->references('id')->on('topup')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_e_wallet');
    }
};
