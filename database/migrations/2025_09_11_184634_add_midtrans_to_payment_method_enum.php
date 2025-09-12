<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `payment_method` ENUM('cash','card','qris','transfer','midtrans') NOT NULL DEFAULT 'cash'");
            return;
        }

        if ($driver === 'pgsql') {
            // Drop existing check constraint if present, then add new one including 'midtrans'
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check");
            $constraints = DB::select("
                SELECT conname FROM pg_constraint c
                JOIN pg_class t ON c.conrelid = t.oid
                WHERE t.relname = 'orders' AND conname ILIKE '%payment_method%'
            ");
            foreach ($constraints as $c) {
                $name = $c->conname ?? null;
                if ($name) {
                    DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS \"{$name}\"");
                }
            }
            // Ensure column is text/varchar, not a custom enum
            DB::statement("ALTER TABLE orders ALTER COLUMN payment_method TYPE VARCHAR(20)");
            // Add new check constraint
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_payment_method_check CHECK (payment_method IN ('cash','card','qris','transfer','midtrans'))");
            return;
        }

        // For sqlite or others: ensure column exists; skip strict enum handling
        // No action needed as sqlite doesn't enforce enum; application validation handles values
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `payment_method` ENUM('cash','card','qris','transfer') NOT NULL DEFAULT 'cash'");
            return;
        }

        if ($driver === 'pgsql') {
            // Revert check constraint to original (without 'midtrans')
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check");
            $constraints = DB::select("
                SELECT conname FROM pg_constraint c
                JOIN pg_class t ON c.conrelid = t.oid
                WHERE t.relname = 'orders' AND conname ILIKE '%payment_method%'
            ");
            foreach ($constraints as $c) {
                $name = $c->conname ?? null;
                if ($name) {
                    DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS \"{$name}\"");
                }
            }
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_payment_method_check CHECK (payment_method IN ('cash','card','qris','transfer'))");
            return;
        }

        // sqlite/others: no-op
    }
};