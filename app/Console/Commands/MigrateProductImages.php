<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;

class MigrateProductImages extends Command
{
    protected $signature = 'products:migrate-images {--dry-run}';
    protected $description = 'Migrate product base64 images to public storage files';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $migrated = 0;

        Product::whereNotNull('image')->chunk(100, function ($products) use (&$migrated, $dryRun) {
            foreach ($products as $product) {
                $image = $product->image;
                if (is_string($image) && str_starts_with($image, 'data:')) {
                    if ($dryRun) {
                        $this->line("Would migrate product #{$product->id} ({$product->name})");
                        continue;
                    }
                    $path = $this->storeBase64Image($image);
                    $product->image = $path;
                    $product->save();
                    $migrated++;
                }
            }
        });

        $this->info("Migration completed. Migrated {$migrated} product image(s).");
        return Command::SUCCESS;
    }

    private function storeBase64Image(string $dataUri): string
    {
        if (!preg_match('/^data:(.*?);base64,(.*)$/', $dataUri, $matches)) {
            throw new \InvalidArgumentException('Invalid base64 image data');
        }
        $mime = $matches[1] ?? 'application/octet-stream';
        $base64 = $matches[2] ?? '';
        $binary = base64_decode($base64);
        if ($binary === false) {
            throw new \InvalidArgumentException('Failed to decode base64 image');
        }
        $extension = match ($mime) {
            'image/jpeg','image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'bin',
        };
        $filename = 'products/' . Str::uuid()->toString() . '.' . $extension;
        Storage::disk('public')->put($filename, $binary);
        return $filename;
    }
}


