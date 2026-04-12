<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 3, 2)->default(4.50)->after('image_url');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number', 32)->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 32)->default('pending')->index()->after('status');
            }
        });

        foreach (DB::table('orders')->whereNull('order_number')->cursor() as $row) {
            DB::table('orders')->where('id', $row->id)->update([
                'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            ]);
        }

        DB::table('orders')->where('status', 'pending')->update(['status' => 'placed']);

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropUnique(['order_number']);
                $table->dropColumn('order_number');
            }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
