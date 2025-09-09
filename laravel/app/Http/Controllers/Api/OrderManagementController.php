<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
	// GET /api/orders/grouped-by-customer?status=pending
	public function groupedByCustomer(Request $request)
	{
		$status = $request->query('status');
		$ordersQuery = Order::with(['user', 'items.product']);
		if ($status && strtolower($status) !== 'all') {
			$ordersQuery->where('status', $status);
		}
		$orders = $ordersQuery->get();

		$grouped = $orders->groupBy('user_id')->map(function ($orders, $userId) {
			$user = $orders->first()->user;
			return [
				'customer' => [
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
				],
				'orders' => $orders->map(function ($order) {
					// Format items summary: "Product A × 2"
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

		return response()->json($grouped);
	}

	// POST /api/orders/{order}/approve
	public function approve(Order $order)
	{
		$order->status = 'approved';
		$order->save();
		return response()->json(['message' => 'Order approved', 'order' => $order]);
	}

	// POST /api/orders/{order}/reject
	public function reject(Order $order)
	{
		$order->status = 'rejected';
		$order->save();
		return response()->json(['message' => 'Order rejected', 'order' => $order]);
	}
}
