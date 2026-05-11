<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('batch_no');
            $table->date('expiry_date');
            $table->decimal('mrp', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->timestamps();
            
            $table->index('batch_no');
            $table->index('expiry_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
};