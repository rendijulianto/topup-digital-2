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
        Schema::create('topup_voucher', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('topup_id');
            $table->datetime('expired_at');
            $table->foreign('topup_id')->references('id')->on('topups')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_voucher');
    }
};
