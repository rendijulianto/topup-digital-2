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
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->string('product_sku_code', 30)->unique();
            $table->double('price', 15, 2);
            $table->integer('stock');
            $table->boolean('status')->default(false);
            $table->boolean('multi')->default(false);
            $table->time('start_cut_off')->nullable();
            $table->time('end_cut_off')->nullable();
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE product_suppliers MODIFY stock INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_suppliers');
    }
};
