<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = ['name', 'desc', 'image', 'thumb', 'status', 'admin_id'];

	public function admin()
	{
		return $this->belongsTo(User::class, 'admin_id');
	}

}
