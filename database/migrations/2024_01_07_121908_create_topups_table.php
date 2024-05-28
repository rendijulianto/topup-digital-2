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
        Schema::create('topups', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('cashier_id')->nullable();
            $table->string('target');
            $table->string('note')->nullable();
            $table->double('price_sell', 15, 2);
            $table->double('price_buy', 15, 2);
            $table->string('whatsapp', 15)->nullable();
            $table->enum('status', ['sukses', 'gagal', 'pending'])->default('pending');
            $table->enum('type', ['seluler', 'voucher', 'token_listrik', 'e_wallet'])->default('seluler');
            $table->dateTime('transacted_at')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');    
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE topups MODIFY category_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topups MODIFY type_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topups MODIFY brand_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE topups MODIFY cashier_id INT(3) UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topups');
    }
};
