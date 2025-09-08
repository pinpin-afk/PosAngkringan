<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'processed_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('processed_by')->nullable()->after('customer_phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'processed_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('processed_by');
            });
        }
    }
};


