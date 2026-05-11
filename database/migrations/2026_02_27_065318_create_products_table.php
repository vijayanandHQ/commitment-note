<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->nullable()->unique();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sale_unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('mrp', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('name');
            $table->index('product_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};