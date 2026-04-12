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
            if (! Schema::hasColumn('products', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
            if (! Schema::hasColumn('products', 'category')) {
                $table->string('category')->index()->after('slug');
            }
            if (! Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (! Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)->after('description');
            }
            if (! Schema::hasColumn('products', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('price');
            }
            if (! Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->index()->after('stock');
            }
            if (! Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->index()->after('is_featured');
            }
            if (! Schema::hasColumn('products', 'image_url')) {
                $table->string('image_url', 2048)->nullable()->after('is_active');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('orders', 'status')) {
                $table->string('status', 32)->default('pending')->index()->after('user_id');
            }
            if (! Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 14, 2)->default(0)->after('status');
            }
            if (! Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('total');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->after('order_id')->constrained()->restrictOnDelete();
            }
            if (! Schema::hasColumn('order_items', 'quantity')) {
                $table->unsignedInteger('quantity')->after('product_id');
            }
            if (! Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->after('quantity');
            }
            if (! Schema::hasColumn('order_items', 'line_total')) {
                $table->decimal('line_total', 14, 2)->after('unit_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'order_id')) {
                $table->dropForeign(['order_id']);
            }
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->dropForeign(['product_id']);
            }
            $table->dropColumn([
                'order_id',
                'product_id',
                'quantity',
                'unit_price',
                'line_total',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            $table->dropColumn([
                'user_id',
                'status',
                'total',
                'notes',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'slug',
                'category',
                'description',
                'price',
                'stock',
                'is_featured',
                'is_active',
                'image_url',
            ]);
        });
    }
};
