<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderManagementController extends Controller
{
	// Reusable method to get grouped orders
	// private function getGroupedOrders($status = null)
	// {
	// 	$admin = auth()->user();

	// 	// Get all users (customers) for this admin
	// 	$customerIds = \App\Models\User::where('parent_admin_id', $admin->id)->pluck('id');

	// 	$ordersQuery = Order::with(['user', 'items.product'])
	// 		->whereIn('user_id', $customerIds);

	// 	if ($status && strtolower($status) !== 'all') {
	// 		$ordersQuery->where('status', $status);
	// 	}

	// 	$orders = $ordersQuery->get();

	// 	return $orders->groupBy('user_id')->map(function ($orders, $userId) {
	// 		$user = $orders->first()->user;
	// 		return [
	// 			'customer' => [
	// 				'id' => $user->id,
	// 				'name' => $user->name,
	// 				'email' => $user->email,
	// 				'phone' => $user->phone,
	// 			],
	// 			'orders' => $orders->map(function ($order) {
	// 				$itemsSummary = $order->items->map(function ($item) {
	// 					$productName = $item->product->name ?? 'Product';
	// 					return $productName . ' × ' . $item->qty;
	// 				})->implode(', ');

	// 				return [
	// 					'id' => $order->id,
	// 					'order_number' => $order->order_number,
	// 					'customer_name' => $order->user->name,
	// 					'items_summary' => $itemsSummary,
	// 					'delivery_date' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M j, Y') : null,
	// 					'status' => $order->status,
	// 					'approve_url' => route('orders.approve', $order->id),
	// 					'reject_url' => route('orders.reject', $order->id),
	// 				];
	// 			})->values(),
	// 		];
	// 	})->values();
	// }

	private function getGroupedOrders($status = null)
	{
		$user = auth()->user();

		$ordersQuery = Order::with(['user', 'items.product']);

		if ($user->role === 'admin') {

			// Admin can only see orders of their customers (soft deleted excluded)
			$customerIds = User::where('parent_admin_id', $user->id)->pluck('id');
			$ordersQuery->whereIn('user_id', $customerIds);
		}

		if ($user->role === 'super_admin') {
			// Super admin can see all orders including soft deleted
			// so that they can restore if needed
			$ordersQuery->withTrashed();
		}

		if ($status && strtolower($status) !== 'all') {
			$ordersQuery->where('status', $status);
		}

		$orders = $ordersQuery->get();

		return $orders->groupBy('user_id')->map(function ($orders, $userId) {
			$user = $orders->first()->user;
			return [
				'customer' => [
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
				],
				'orders' => $orders->map(function ($order) {
					$itemsSummary = $order->items->map(function ($item) {
						$productName = $item->product->name ?? 'Product';
						return $productName . ' × ' . $item->qty;
					})->implode(', ');

					return [
						'id' => $order->id,
						'order_number' => $order->order_number,
						'customer_name' => $order->user->name,
						'items_summary' => $itemsSummary,
						'delivery_date' => $order->delivery_date
							? \Carbon\Carbon::parse($order->delivery_date)->format('M j, Y')
							: null,
						'status' => $order->status,
						'deleted_at' => $order->deleted_at, // 👈 Super Admin deleted order চিনবে
					];
				})->values(),
			];
		})->values();
	}


	// GET /api/orders/grouped-by-customer?status=pending
	public function groupedByCustomer(Request $request)
	{
		$status = $request->query('status');
		$grouped = $this->getGroupedOrders($status);

		return response()->json($grouped);
	}


	// PUT /api/orders/{order}
	public function update(Request $request, Order $order)
	{

		// \Log::info('Update order attempt', [
		// 	'order_id' => $order->id,
		// 	'status' => $order->status,
		// 	'user_id' => $order->user_id,
		// 	'auth_id' => auth()->id(),
		// ]);

		// 1. Ownership check
		if ($order->user_id !== auth()->id()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		// 2. Only pending orders can be updated
		if (strtolower($order->status) !== 'pending') {
			return response()->json(['message' => 'Order can only be updated while pending'], 400);
		}

		// 3. Validate order-level fields
		$validated = $request->validate([
			'delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
			'notes' => ['nullable', 'string', 'max:500'],
			'shipping_name' => ['nullable', 'string', 'max:255'],
			'shipping_phone' => ['nullable', 'string', 'max:20'],
			'shipping_address_1' => ['nullable', 'string', 'max:255'],
			'shipping_address_2' => ['nullable', 'string', 'max:255'],
			'shipping_city' => ['nullable', 'string', 'max:100'],
			'shipping_state' => ['nullable', 'string', 'max:100'],
			'shipping_postal_code' => ['nullable', 'string', 'max:20'],
			'shipping_country' => ['nullable', 'string', 'max:100'],

			// Order items
			'items' => ['required', 'array', 'min:1'],
			'items.*.id' => ['required', Rule::exists('order_items', 'id')->where('order_id', $order->id)],
			'items.*.qty' => ['required', 'integer', 'min:1'],
		]);

		// 4. Update order details
		$order->update([
			'delivery_date' => $validated['delivery_date'] ?? $order->delivery_date,
			'notes' => $validated['notes'] ?? $order->notes,
			'shipping_name' => $validated['shipping_name'] ?? $order->shipping_name,
			'shipping_phone' => $validated['shipping_phone'] ?? $order->shipping_phone,
			'shipping_address_1' => $validated['shipping_address_1'] ?? $order->shipping_address_1,
			'shipping_address_2' => $validated['shipping_address_2'] ?? $order->shipping_address_2,
			'shipping_city' => $validated['shipping_city'] ?? $order->shipping_city,
			'shipping_state' => $validated['shipping_state'] ?? $order->shipping_state,
			'shipping_postal_code' => $validated['shipping_postal_code'] ?? $order->shipping_postal_code,
			'shipping_country' => $validated['shipping_country'] ?? $order->shipping_country,
		]);

		// 5. Update items (qty)
		foreach ($validated['items'] as $itemData) {
			$item = OrderItem::where('order_id', $order->id)->where('id', $itemData['id'])->first();
			if ($item) {
				$item->update(['qty' => $itemData['qty']]);
			}
		}

		return response()->json([
			'message' => 'Order updated successfully',
			'order' => $order->load('items.product'),
		]);
	}


	// POST /api/orders/{order}/approve
	public function approve(Request $request, Order $order)
	{
		if ($order->status !== 'pending') {
			return response()->json(['message' => 'Order already processed'], 400);
		}


		$order->update(['status' => 'approved']);

		\Log::info("Dispatching OrderStatusUpdated event", [
			'order_id' => $order->id,
			'status' => $order->status,
			'customerId' => $order->customer_id,
		]);

		// Fire broadcast event for customer
		event(new OrderStatusUpdated($order->id, 'approved', $order->user_id));

		// return updated grouped list
		$status = $request->query('status', 'all');
		$grouped = $this->getGroupedOrders($status);

		return response()->json([
			'message' => 'Order approved',
			'orders' => $grouped
		]);
	}

	// POST /api/orders/{order}/reject
	public function reject(Request $request, Order $order)
	{
		if ($order->status !== 'pending') {
			return response()->json(['message' => 'Order already processed'], 400);
		}


		$order->update(['status' => 'rejected']);
		// Fire broadcast event for customer
		event(new OrderStatusUpdated($order->id, 'rejected', $order->user_id));

		// return updated grouped list
		$status = $request->query('status', 'all');
		$grouped = $this->getGroupedOrders($status);

		return response()->json([
			'message' => 'Order rejected',
			'orders' => $grouped
		]);
	}

	// GET /api/orders/{order}
	public function show(Request $request, Order $order)
	{
		// Ownership check: customer can only view own order
		if ($order->user_id !== auth()->id()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		// Load items with product snapshot
		$order->load('items.product');

		return response()->json([
			'order' => $order
		]);
	}

	// DELETE /api/customers/{customer}/orders
	public function destroyByCustomer($customerId)
	{
		$user = auth()->user();

		if ($user->role !== 'admin') {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		// ঐ customer এর সব order soft delete করে দাও
		$orders = Order::where('user_id', $customerId)->get();

		foreach ($orders as $order) {
			$order->delete();
		}

		return response()->json(['message' => 'All orders for this customer deleted successfully']);
	}


}
