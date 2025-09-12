<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('category')
                ->where('is_active', true)
                ->get();
            
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            $data = $request->only(['name','description','price','stock','category_id','is_active']);

            // Handle image upload to storage (public disk)
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image_path'] = $path; // store relative path like products/xxx.jpg
            } elseif (is_string($request->input('image')) && str_starts_with($request->input('image'), 'data:')) {
                // Support base64 data URI uploads for backward compatibility
                $data['image_path'] = $this->storeBase64Image($request->input('image'));
            }

            $product = Product::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product->load('category')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            $data = $request->only(['name','description','price','stock','category_id','is_active']);

            // Handle image upload/removal to storage (public disk)
            if ($request->hasFile('image')) {
                // Delete old stored file if exists and is a stored path
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $path = $request->file('image')->store('products', 'public');
                $data['image_path'] = $path;
            } elseif ($request->boolean('remove_image')) {
                // Explicit remove current image
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = null;
            } elseif (is_string($request->input('image')) && str_starts_with($request->input('image'), 'data:')) {
                // If incoming still base64, migrate to storage
                // Delete old stored file if exists (optional)
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $this->storeBase64Image($request->input('image'));
            }

            $product->update($data);


            $productData = $product->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $productData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {;
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        // Delete stored image file if exists and not base64
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return response()->json(null, 204);
    }

    /**
     * Store base64 data URI image to public storage and return relative path
     */
    private function storeBase64Image(string $dataUri): string
    {
        // data:image/png;base64,XXXX
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
