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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private', 'restricted'])
                ->default('public')
                ->after('is_active')
                ->comment('Controls who can see this product');
            
            $table->enum('availability', ['coming_soon', 'available', 'discontinued'])
                ->default('available')
                ->after('visibility')
                ->comment('Product availability status');
            
            $table->boolean('is_featured')->default(false)->after('availability');
            $table->boolean('show_on_homepage')->default(false)->after('is_featured');
            $table->boolean('is_highlighted')->default(false)->after('show_on_homepage');
            
            $table->date('publish_at')->nullable()->after('is_highlighted')
                ->comment('Date when the product should be published');
            
            $table->date('unpublish_at')->nullable()->after('publish_at')
                ->comment('Date when the product should be unpublished');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'visibility',
                'availability',
                'is_featured',
                'show_on_homepage',
                'is_highlighted',
                'publish_at',
                'unpublish_at'
            ]);
        });
    }
};
