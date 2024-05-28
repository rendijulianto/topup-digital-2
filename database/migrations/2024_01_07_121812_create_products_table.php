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
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name', 150);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('type_id');
            $table->double('price', 15, 2)->default(0);
            $table->string('description');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE products MODIFY category_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE products MODIFY brand_id INT(4) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE products MODIFY type_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
