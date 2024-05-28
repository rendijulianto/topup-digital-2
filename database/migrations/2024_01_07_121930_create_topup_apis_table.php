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
            $table->unsignedInteger('product_supplier_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('user_id');
            $table->string('ref_id', 20)->unique();
            $table->string('trx_id', 50)->nullable();
            $table->string('note', 255)->nullable();
            $table->enum('status', ['sukses','gagal','pending'])->default('pending');
            $table->timestamps();
            $table->foreign('topup_id')->references('id')->on('topups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_supplier_id')->references('id')->on('product_suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE topup_api MODIFY supplier_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topup_api MODIFY user_id INT(3) UNSIGNED NOT NULL');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_api');
    }
};
