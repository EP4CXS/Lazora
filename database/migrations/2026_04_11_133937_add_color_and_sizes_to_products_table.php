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
            if (! Schema::hasColumn('products', 'color')) {
                $table->string('color', 128)->nullable()->after('category');
            }
            if (! Schema::hasColumn('products', 'sizes')) {
                $table->string('sizes', 255)->nullable()->after('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sizes')) {
                $table->dropColumn('sizes');
            }
            if (Schema::hasColumn('products', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
