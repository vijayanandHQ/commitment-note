<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update existing medicines table with new columns
        if (Schema::hasTable('medicines')) {
            Schema::table('medicines', function (Blueprint $table) {
                if (!Schema::hasColumn('medicines', 'product_code')) {
                    $table->string('product_code')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('medicines', 'purchase_price')) {
                    $table->decimal('purchase_price', 10, 2)->nullable()->after('price');
                }
                if (!Schema::hasColumn('medicines', 'supplier_code')) {
                    $table->string('supplier_code')->nullable()->after('manufacturer');
                }
                if (!Schema::hasColumn('medicines', 'supplier_name')) {
                    $table->string('supplier_name')->nullable()->after('supplier_code');
                }
                if (!Schema::hasColumn('medicines', 'purchase_unit')) {
                    $table->integer('purchase_unit')->nullable()->after('purchase_price');
                }
                if (!Schema::hasColumn('medicines', 'sale_unit')) {
                    $table->integer('sale_unit')->nullable()->after('purchase_unit');
                }
                if (!Schema::hasColumn('medicines', 'alt_supplier_codes')) {
                    $table->string('alt_supplier_codes')->nullable()->after('supplier_name');
                }
                if (!Schema::hasColumn('medicines', 'generic_name_original')) {
                    $table->string('generic_name_original')->nullable()->after('generic_name');
                }
            });
        }

        // Update existing suppliers table
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (!Schema::hasColumn('suppliers', 'supplier_code')) {
                    $table->string('supplier_code')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('suppliers', 'alt_supplier_code')) {
                    $table->string('alt_supplier_code')->nullable()->after('supplier_code');
                }
            });
        }

        // Create new tables if they don't exist
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
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

        if (!Schema::hasTable('product_suppliers')) {
            Schema::create('product_suppliers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
                $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->nullOnDelete();
                $table->decimal('purchase_price', 10, 2);
                $table->timestamps();
                
                $table->unique(['product_id', 'supplier_id']);
            });
        }

        if (!Schema::hasTable('batches')) {
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
    }

    public function down()
    {
        // Drop new tables only
        Schema::dropIfExists('batches');
        Schema::dropIfExists('product_suppliers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
        
        // Remove added columns from medicines
        if (Schema::hasTable('medicines')) {
            Schema::table('medicines', function (Blueprint $table) {
                $columns = ['product_code', 'purchase_price', 'supplier_code', 'supplier_name', 
                           'purchase_unit', 'sale_unit', 'alt_supplier_codes', 'generic_name_original'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('medicines', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};