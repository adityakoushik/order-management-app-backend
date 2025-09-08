<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
	protected $fillable = [
		'order_id',
		'product_id',
		'name',
		'qty',
		'thumb_url',
		'image_url',
		'meta'
	];

	protected $casts = ['meta' => 'array'];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
