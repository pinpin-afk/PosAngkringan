<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'midtrans_order_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('midtrans_order_id')->nullable()->after('order_number');
            });
        }

        if (!Schema::hasColumn('orders', 'midtrans_transaction_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            });
        }

        if (!Schema::hasColumn('orders', 'midtrans_signature')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('midtrans_signature')->nullable()->after('midtrans_transaction_id');
            });
        }

        if (!Schema::hasColumn('orders', 'payment_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_type')->nullable()->after('payment_method');
            });
        }

        if (!Schema::hasColumn('orders', 'paid_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id','midtrans_transaction_id','midtrans_signature','paid_at','payment_type']);
        });
    }
};


