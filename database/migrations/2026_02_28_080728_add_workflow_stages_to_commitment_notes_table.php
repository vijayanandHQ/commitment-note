<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('commitment_notes', function (Blueprint $table) {
            $table->enum('workflow_stage', ['pending_supplier', 'received_from_supplier', 'customer_contacted', 'delivered'])
                  ->default('pending_supplier')->after('status');
            $table->enum('delivery_type', ['home', 'medical'])->nullable()->after('workflow_stage');
            $table->timestamp('supplier_asked_at')->nullable()->after('delivery_type');
            $table->timestamp('received_at')->nullable()->after('supplier_asked_at');
            $table->timestamp('customer_contacted_at')->nullable()->after('received_at');
            $table->timestamp('delivered_at')->nullable()->after('customer_contacted_at');
        });
    }

    public function down()
    {
        Schema::table('commitment_notes', function (Blueprint $table) {
            $table->dropColumn(['workflow_stage', 'delivery_type', 'supplier_asked_at', 'received_at', 'customer_contacted_at', 'delivered_at']);
        });
    }
};