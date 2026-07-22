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
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('id');
            }

            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->after('category_id');
            }

            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('name');
            }

            if (!Schema::hasColumn('products', 'quantity')) {
                $table->unsignedInteger('quantity')->default(0)->after('price');
            }

            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('products', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('products', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
