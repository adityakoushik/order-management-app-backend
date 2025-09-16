<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

	use SoftDeletes;
	protected $fillable = [
		'user_id',
		'order_number',
		'status',
		'delivery_date',
		'notes',
		'shipping_name',
		'shipping_phone',
		'shipping_address_1',
		'shipping_address_2',
		'shipping_city',
		'shipping_state',
		'shipping_postal_code',
		'shipping_country',
		'channel',
		'meta'
	];

	protected $casts = ['delivery_date' => 'date', 'meta' => 'array'];

	public function items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
