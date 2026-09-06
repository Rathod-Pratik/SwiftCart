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
        Schema::create('product_information_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('section_id')
                ->constrained('product_information_sections')
                ->cascadeOnDelete();

            $table->string('label');

            $table->text('value');

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_information_items');
    }
};
