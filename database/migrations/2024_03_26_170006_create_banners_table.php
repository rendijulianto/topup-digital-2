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
        Schema::create('banner', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('judul', 50);
            $table->string('gambar', 60);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE banner MODIFY id INT(4) UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
