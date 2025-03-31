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
        Schema::create('product_variation_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variation_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('fk_product_variation');
            $table->foreignId('product_attribute_value_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('fk_product_attribute_value');
            $table->timestamps();

            // Each attribute value should only be used once per variation
            $table->unique(
                ['product_variation_id', 'product_attribute_value_id'],
                'variation_attribute_value_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_attribute_values');
    }
};
