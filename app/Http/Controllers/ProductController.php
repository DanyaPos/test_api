<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function fetchIphones(): \Illuminate\Http\JsonResponse
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->get('https://dummyjson.com/products/category/smartphones');

            if (!$response->successful()) {
                Log::error('API request failed', ['status' => $response->status()]);
                return response()->json(['error' => 'Failed to fetch data from API'], 500);
            }



            $products = collect($response->json()['products'])
                ->filter(fn($product) => stripos($product['title'], 'iPhone') !== false);



            $savedProducts = $products->map(function ($productData) {
                return Product::updateOrCreate(
                    ['dummy_id' => $productData['id']],
                    [
                        'title' => $productData['title'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'discount_percentage' => $productData['discountPercentage'],
                        'rating' => $productData['rating'],
                        'stock' => $productData['stock'],
                        'tags' => $productData['tags'] ?? null,
                        'brand' => $productData['brand'],
                        'category' => $productData['category'],
                        'sku' => $productData['sku'] ?? null,
                        'weight' => $productData['weight'] ?? null,
                        'dimensions' => $productData['dimensions'] ?? null,
                        'warranty_information' => $productData['warrantyInformation'] ?? null,
                        'shipping_information' => $productData['shippingInformation'] ?? null,
                        'availability_status' => $productData['availabilityStatus'] ?? null,
                        'reviews' => $productData['reviews'] ?? null,
                        'return_policy' => $productData['returnPolicy'] ?? null,
                        'minimum_order_quantity' => $productData['minimumOrderQuantity'] ?? null,
                        'meta' => $productData['meta'] ?? null,
                        'images' => json_encode($productData['images'] ?? []),
                        'thumbnail' => $productData['thumbnail'] ?? null,
                    ]
                );

            });

            return response()->json($savedProducts);

        } catch (\Exception $e) {
            Log::error('Error in fetchIphones: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getIphones(): \Illuminate\Http\JsonResponse
    {
        try {
            $iphones = Product::where('title', 'like', '%iPhone%')
                ->where('category', 'smartphones');

            return response()->json($iphones);

        } catch (\Exception $e) {
            Log::error('Error in getIphones: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    // Добавляем новый продукт
    public function addProduct(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Валидируем данные
            $validated = $request->validate([
                'dummy_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'stock' => 'nullable|integer|min:0',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|string',
                'brand' => 'nullable|string|max:100',
                'sku' => 'nullable|string|max:100',
                'weight' => 'nullable|numeric',
                'dimensions' => 'nullable|array',
                'dimensions.width' => 'nullable|numeric',
                'dimensions.height' => 'nullable|numeric',
                'dimensions.depth' => 'nullable|numeric',
                'warranty_information' => 'nullable|string',
                'shipping_information' => 'nullable|string',
                'availability_status' => 'nullable|string',
                'reviews' => 'nullable|array',
                'reviews.*.rating' => 'nullable|numeric|min:0|max:5',
                'reviews.*.comment' => 'nullable|string',
                'return_policy' => 'nullable|string',
                'minimum_order_quantity' => 'nullable|integer|min:1',
                'meta' => 'nullable|array',
                'images' => 'nullable|array',
                'images.*' => 'nullable|string',
                'thumbnail' => 'nullable|string',
            ]);

            // Создаем новый продукт
            $product = Product::create([
                'dummy_id' => $validated['dummy_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'discount_percentage' => $validated['discount_percentage'] ?? null,
                'rating' => $validated['rating'] ?? null,
                'stock' => $validated['stock'] ?? null,
                'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
                'brand' => $validated['brand'] ?? null,
                'sku' => $validated['sku'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'dimensions' => $validated['dimensions'] ? json_encode($validated['dimensions']) : null,
                'warranty_information' => $validated['warranty_information'] ?? null,
                'shipping_information' => $validated['shipping_information'] ?? null,
                'availability_status' => $validated['availability_status'] ?? null,
                'reviews' => $validated['reviews'] ? json_encode($validated['reviews']) : null,
                'return_policy' => $validated['return_policy'] ?? null,
                'minimum_order_quantity' => $validated['minimum_order_quantity'] ?? null,
                'meta' => $validated['meta'] ? json_encode($validated['meta']) : null,
                'images' => $validated['images'] ? json_encode($validated['images']) : null,
                'thumbnail' => $validated['thumbnail'] ?? null,
            ]);

            // Возвращаем ответ с созданным продуктом
            return response()->json($product, 201); // Ответ с созданным продуктом
        } catch (\Exception $e) {
            Log::error('Error in store product: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
