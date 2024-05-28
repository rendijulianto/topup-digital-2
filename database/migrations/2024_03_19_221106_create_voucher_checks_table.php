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
        Schema::create('voucher_checks', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('ref_id',20)->unique();
            $table->string('target', 50);
            $table->string('note',255);
            $table->enum('status', ['sukses', 'gagal', 'pending'])->default('pending');
            $table->unsignedInteger('brand_id');
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE voucher_checks MODIFY brand_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_checks');
    }
};
