<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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

            $data = $request->all();
            
            // Handle image upload - save as base64
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageData = base64_encode(file_get_contents($image->getRealPath()));
                $data['image'] = 'data:' . $image->getMimeType() . ';base64,' . $imageData;
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
            \Log::info('Product update request:', [
                'product_id' => $product->id,
                'request_data' => $request->all(),
                'has_image' => $request->hasFile('image'),
                'image_file' => $request->hasFile('image') ? [
                    'name' => $request->file('image')->getClientOriginalName(),
                    'size' => $request->file('image')->getSize(),
                    'mime' => $request->file('image')->getMimeType()
                ] : null
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            
            // Handle image upload - save as base64
            if ($request->hasFile('image')) {
                \Log::info('Processing image upload for product: ' . $product->id);
                
                $image = $request->file('image');
                $imageData = base64_encode(file_get_contents($image->getRealPath()));
                $data['image'] = 'data:' . $image->getMimeType() . ';base64,' . $imageData;
                
                \Log::info('Image converted to base64 successfully');
            }

            \Log::info('Updating product with data:', $data);
            $product->update($data);

            \Log::info('Product updated successfully: ' . $product->id);

            $productData = $product->load('category');
            \Log::info('Product data being returned:', [
                'id' => $productData->id,
                'name' => $productData->name,
                'image' => $productData->image,
                'image_url' => $productData->image_url
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $productData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for product update:', [
                'product_id' => $product->id,
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $product->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
