<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
	// 📌 Reusable method to get grouped orders
	private function getGroupedOrders($status = null)
	{
		$ordersQuery = Order::with(['user', 'items.product']);

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
						'delivery_date' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M j, Y') : null,
						'status' => $order->status,
						'approve_url' => route('orders.approve', $order->id),
						'reject_url' => route('orders.reject', $order->id),
					];
				})->values(),
			];
		})->values();
	}

	// 📌 GET /api/orders/grouped-by-customer?status=pending
	public function groupedByCustomer(Request $request)
	{
		$status = $request->query('status');
		$grouped = $this->getGroupedOrders($status);

		return response()->json($grouped);
	}

	// 📌 POST /api/orders/{order}/approve
	public function approve(Request $request, Order $order)
	{
		if ($order->status !== 'pending') {
			return response()->json(['message' => 'Order already processed'], 400);
		}


		$order->update(['status' => 'approved']);
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

	// 📌 POST /api/orders/{order}/reject
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
}
