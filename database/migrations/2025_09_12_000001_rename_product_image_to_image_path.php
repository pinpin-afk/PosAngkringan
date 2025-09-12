<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('category_id');
        });

        // Copy existing non-base64 image values to image_path
        DB::table('products')->whereNotNull('image')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                $image = $row->image;
                if (is_string($image) && !str_starts_with($image, 'data:')) {
                    DB::table('products')->where('id', $row->id)->update(['image_path' => $image]);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('image')->nullable()->after('category_id');
        });

        // Move back image_path to image
        DB::table('products')->whereNotNull('image_path')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('products')->where('id', $row->id)->update(['image' => $row->image_path]);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};


