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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'verified', 'paid', 'failed'])->default('pending')->after('payment_type');
            $table->timestamp('transfer_verified_at')->nullable()->after('payment_status');
            $table->text('transfer_notes')->nullable()->after('transfer_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'transfer_verified_at', 'transfer_notes']);
        });
    }
};
