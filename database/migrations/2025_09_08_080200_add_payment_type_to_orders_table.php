<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'payment_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_type')->nullable()->after('payment_method');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'payment_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_type');
            });
        }
    }
};


