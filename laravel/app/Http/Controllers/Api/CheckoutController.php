<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\AddCheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
	public function checkout(AddCheckoutRequest $request)
	{
		$user = $request->user();
		$data = $request->validated(); // delivery_date, notes, (optional shipping fields)

		$cart = Cart::with('items.product')
			->where('user_id', $user->id)
			->first();

		if (!$cart || $cart->items->isEmpty()) {
			return response()->json(['message' => 'Cart is empty'], 400);
		}

		$order = DB::transaction(function () use ($user, $cart, $data) {

			$order = Order::create([
				'user_id' => $user->id,
				'order_number' => $this->generateOrderNumber(),
				'status' => 'pending',
				'delivery_date' => $data['delivery_date'],
				'notes' => $data['notes'] ?? null,
				'channel' => 'mobile_app',
				// If you capture shipping now, add the snapshot fields here from $data
				'shipping_name' => $data['shipping_name'] ?? null,
				'shipping_phone' => $data['shipping_phone'] ?? null,
				'shipping_address_1' => $data['shipping_address_1'] ?? null,
				'shipping_address_2' => $data['shipping_address_2'] ?? null,
				'shipping_city' => $data['shipping_city'] ?? null,
				'shipping_state' => $data['shipping_state'] ?? null,
				'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
				'shipping_country' => $data['shipping_country'] ?? null,
			]);

			foreach ($cart->items as $item) {
				$p = $item->product;
				if (!$p || $p->status !== 'active') {
					abort(422, 'One or more products are unavailable');
				}

				OrderItem::create([
					'order_id' => $order->id,
					'product_id' => $item->product_id,
					'name' => $p->name,          // snapshot
					'qty' => $item->qty,
					'thumb_url' => $p->thumb_url ?? null, // snapshot
					'image_url' => $p->image_url ?? null, // snapshot
					'meta' => $item->meta ?? null,
				]);
			}

			// Clear cart after creating order items
			$cart->items()->delete();

			return $order->load('items');
		});

		return response()->json([
			'message' => 'Order placed',
			'order' => $order
		], 201);
	}

	protected function generateOrderNumber(): string
	{
		return 'ORD-' . now()->format('Ymd-His') . '-' . str_pad((string) (Order::max('id') + 1), 4, '0', STR_PAD_LEFT);
	}

	public function myOrders(AddCheckoutRequest $r) // or Request if not validating here
	{
		$orders = Order::with('items.product')
			->where('user_id', $r->user()->id)
			->latest()
			->get();

		return response()->json(['orders' => $orders]);
	}
}
