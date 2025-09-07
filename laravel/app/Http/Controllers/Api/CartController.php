<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddItemRequest;
use App\Http\Requests\Cart\UpdateQtyRequest;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
	protected function userCart(Request $request): Cart
	{
		return Cart::firstOrCreate(['user_id' => $request->user()->id]);
	}

	public function index(Request $request)
	{
		$cart = $this->userCart($request)->load(['items.product']);
		return response()->json(['status' => 'success', 'data' => $cart]);
	}

	public function store(AddItemRequest $request)
	{
		$cart = $this->userCart($request);

		$item = CartItem::firstOrNew([
			'cart_id' => $cart->id,
			'product_id' => $request->product_id,
		]);

		$item->qty = ($item->exists ? $item->qty : 0) + $request->integer('qty', 1);

		if ($request->filled('price'))
			$item->price = $request->price;
		if ($request->filled('meta'))
			$item->meta = $request->meta;
		$item->save();

		return response()->json([
			'status' => 'success',
			'message' => 'Item added to cart',
			'data' => $item->load('product')
		], 201);

	}

	public function update(UpdateQtyRequest $request, CartItem $item)
	{
		$this->authorizeItem($request, $item);
		$item->update(['qty' => $request->qty]);
		return response()->json(['status' => 'success', 'data' => $item->load('product')]);
	}

	public function destroy(Request $request, CartItem $item)
	{
		$this->authorizeItem($request, $item);
		$item->delete();
		return response()->json(['status' => 'success', 'message' => 'Item removed']);
	}

	public function clear(Request $request)
	{
		$cart = $this->userCart($request);
		$cart->items()->delete();
		return response()->json(['status' => 'success', 'message' => 'Cart cleared']);
	}

	protected function authorizeItem(Request $request, CartItem $item): void
	{
		$cart = $this->userCart($request);
		abort_unless($item->cart_id === $cart->id, 403, 'Forbidden');
	}


}
