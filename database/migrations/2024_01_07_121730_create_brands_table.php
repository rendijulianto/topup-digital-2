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
        Schema::create('brands', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name', 50)->unique();
            $table->string('logo', 50)->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE brands MODIFY id INT(4) UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
