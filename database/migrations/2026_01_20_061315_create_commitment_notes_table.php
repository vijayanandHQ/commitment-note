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
        Schema::create('commitment_notes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('qty');
            $table->string('product_name');
            $table->decimal('mrp', 10, 2);
            $table->integer('order_qty');
            $table->string('supplier');
            $table->string('customer_phone');
            $table->text('comments')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commitment_notes');
    }
};