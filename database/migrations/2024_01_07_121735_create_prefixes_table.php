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
        Schema::create('prefix', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('brand_id');
            $table->string('nomor', 5);
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement('ALTER TABLE prefix MODIFY id INT(3) UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE prefix MODIFY brand_id INT(4) UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefix');
    }
};
