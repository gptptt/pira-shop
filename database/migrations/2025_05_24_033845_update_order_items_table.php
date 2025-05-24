<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* REF-PR-DB-58-START: Create Order and OrderItem models */
return new class extends Migration
{
    /**
     * Applies schema changes to ensure the `order_items` table exists with required columns and relationships.
     *
     * Creates the `order_items` table with appropriate columns and foreign key constraints if it does not exist. If the table already exists, adds any missing columns (`product_id`, `pricing_plan_id`, `quantity`, `name`, `description`) only if they are absent and their referenced tables exist.
     */
    public function up(): void
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('pricing_plan_id')->nullable()->constrained()->nullOnDelete();
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            // Add columns if they don't exist
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'product_id') && Schema::hasTable('products')) {
                    $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete()->after('order_id');
                }
                if (!Schema::hasColumn('order_items', 'pricing_plan_id') && Schema::hasTable('pricing_plans')) {
                    $table->foreignId('pricing_plan_id')->nullable()->constrained()->nullOnDelete()->after('product_id');
                }
                if (!Schema::hasColumn('order_items', 'quantity')) {
                    $table->integer('quantity')->default(1)->after('pricing_plan_id');
                }
                if (!Schema::hasColumn('order_items', 'name')) {
                    $table->string('name')->after('subtotal');
                }
                if (!Schema::hasColumn('order_items', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
            });
        }
    }

    /**
     * Removes the 'name' and 'description' columns from the 'order_items' table if it exists.
     *
     * This method does not drop the entire table, preserving existing data and structure.
     */
    public function down(): void
    {
        // We won't drop the table since it might be a core table
        // Instead we'll remove the columns we added if needed
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn([
                    'name',
                    'description',
                ]);
            });
        }
    }
};
/* REF-PR-DB-58-END */
