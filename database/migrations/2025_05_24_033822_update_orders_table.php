<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* REF-PR-DB-58-START: Create Order and OrderItem models */
return new class extends Migration
{
    /**
     * Applies the migration to create the "orders" table or add missing columns if the table already exists.
     *
     * If the "orders" table does not exist, it is created with user association, status fields, total amount, billing information, notes, and timestamps. If the table exists, any missing billing, status, or notes columns are added.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('status')->default('pending'); // pending, processing, completed, cancelled, refunded
                $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, refunded
                $table->decimal('total_amount', 10, 2);
                $table->string('billing_name');
                $table->string('billing_email');
                $table->string('billing_address')->nullable();
                $table->string('billing_city')->nullable();
                $table->string('billing_state')->nullable();
                $table->string('billing_zip')->nullable();
                $table->string('billing_country')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Add columns if they don't exist
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'status')) {
                    $table->string('status')->default('pending')->after('user_id');
                }
                if (!Schema::hasColumn('orders', 'payment_status')) {
                    $table->string('payment_status')->default('unpaid')->after('status');
                }
                if (!Schema::hasColumn('orders', 'billing_name')) {
                    $table->string('billing_name')->nullable()->after('total_amount');
                }
                if (!Schema::hasColumn('orders', 'billing_email')) {
                    $table->string('billing_email')->nullable()->after('billing_name');
                }
                if (!Schema::hasColumn('orders', 'billing_address')) {
                    $table->string('billing_address')->nullable()->after('billing_email');
                }
                if (!Schema::hasColumn('orders', 'billing_city')) {
                    $table->string('billing_city')->nullable()->after('billing_address');
                }
                if (!Schema::hasColumn('orders', 'billing_state')) {
                    $table->string('billing_state')->nullable()->after('billing_city');
                }
                if (!Schema::hasColumn('orders', 'billing_zip')) {
                    $table->string('billing_zip')->nullable()->after('billing_state');
                }
                if (!Schema::hasColumn('orders', 'billing_country')) {
                    $table->string('billing_country')->nullable()->after('billing_zip');
                }
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('billing_country');
                }
            });
        }
    }

    /**
     * Removes billing and notes columns from the "orders" table without dropping the table.
     *
     * This reverses the migration by deleting the billing-related and notes fields added in the `up` method, preserving the core structure of the "orders" table.
     */
    public function down(): void
    {
        // We won't drop the table since it's a core table
        // Instead we'll remove the columns we added
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_name',
                'billing_email',
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_zip',
                'billing_country',
                'notes',
            ]);
        });
    }
};
/* REF-PR-DB-58-END */
