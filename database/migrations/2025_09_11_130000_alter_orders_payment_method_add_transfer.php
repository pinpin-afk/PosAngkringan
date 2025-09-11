<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for enum alteration
        DB::statement("ALTER TABLE `orders` MODIFY `payment_method` ENUM('cash','card','qris','transfer') NOT NULL DEFAULT 'cash'");
    }

    public function down(): void
    {
        // Revert back to original enum without 'transfer'
        DB::statement("ALTER TABLE `orders` MODIFY `payment_method` ENUM('cash','card','qris') NOT NULL DEFAULT 'cash'");
    }
};


