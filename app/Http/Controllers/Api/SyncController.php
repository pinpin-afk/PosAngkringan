<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class SyncController extends Controller
{
	public function getMasterUpdates(Request $request)
	{
		$updatedSince = $request->query('updated_since');
		$since = $updatedSince ? Carbon::parse($updatedSince) : null;

		$productsQuery = Product::with('category')->where('is_active', true);
		$categoriesQuery = Category::where('is_active', true);
		$membersQuery = Member::query();

		if ($since) {
			$productsQuery->where('updated_at', '>=', $since);
			$categoriesQuery->where('updated_at', '>=', $since);
			$membersQuery->where('updated_at', '>=', $since);
		}

		return response()->json([
			'now' => Carbon::now()->toIso8601String(),
			'products' => $productsQuery->get(),
			'categories' => $categoriesQuery->get(),
			'members' => $membersQuery->get(),
		]);
	}

	public function pushOfflineOrders(Request $request)
	{
		$validated = $request->validate([
			'orders' => 'required|array|min:1',
			'orders.*.client_temp_id' => 'required|string',
			'orders.*.customer_name' => 'nullable|string',
			'orders.*.customer_phone' => 'nullable|string',
			'orders.*.processed_by' => 'nullable|string',
			'orders.*.payment_method' => 'required|string',
			'orders.*.selected_member' => 'nullable',
			'orders.*.points_earned' => 'nullable|integer',
			'orders.*.items' => 'required|array|min:1',
			'orders.*.items.*.product_id' => 'required|integer|exists:products,id',
			'orders.*.items.*.quantity' => 'required|numeric|min:1',
			'orders.*.cash_amount' => 'nullable|numeric',
			'orders.*.change_amount' => 'nullable|numeric',
		]);

		$mappings = [];

		DB::beginTransaction();
		try {
			foreach ($validated['orders'] as $payload) {
				$clientTempId = $payload['client_temp_id'];

				// Idempotency: skip if already imported (by a unique client_temp_id stored in orders table)
				$existing = Order::where('client_temp_id', $clientTempId)->first();
				if ($existing) {
					$mappings[] = [
						'client_temp_id' => $clientTempId,
						'order_id' => $existing->id,
					];
					continue;
				}

				$order = new Order();
				$order->client_temp_id = $clientTempId;
				$order->customer_name = $payload['customer_name'] ?? null;
				$order->customer_phone = $payload['customer_phone'] ?? null;
				$order->processed_by = $payload['processed_by'] ?? null;
				$order->payment_status = 'paid';
				$order->payment_method = $payload['payment_method'];
				$order->selected_member = $payload['selected_member'] ?? null;
				$order->points_earned = $payload['points_earned'] ?? 0;
				$order->save();

				foreach ($payload['items'] as $it) {
					OrderItem::create([
						'order_id' => $order->id,
						'product_id' => $it['product_id'],
						'quantity' => $it['quantity'],
						'price' => Product::find($it['product_id'])->price,
					]);
				}

				$mappings[] = [
					'client_temp_id' => $clientTempId,
					'order_id' => $order->id,
				];
			}

			DB::commit();
		} catch (\Throwable $e) {
			DB::rollBack();
			return response()->json(['message' => 'Sync failed', 'error' => $e->getMessage()], 422);
		}

		return response()->json(['mappings' => $mappings]);
	}
}
