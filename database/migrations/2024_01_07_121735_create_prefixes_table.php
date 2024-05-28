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
        Schema::create('prefixs', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('brand_id');
            $table->string('number', 5);
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE prefixs MODIFY id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE prefixs MODIFY brand_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefixs');
    }
};
