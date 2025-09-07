<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
	protected $fillable = ['name', 'desc', 'image', 'thumb', 'status', 'admin_id'];

	// JSON e computed fields auto include [3]
	protected $appends = ['image_url', 'thumb_url'];

	public function admin()
	{
		return $this->belongsTo(User::class, 'admin_id');
	}

	// Full URL from public disk (respects disks.public.url)
	public function getImageUrlAttribute(): ?string
	{
		return $this->image ? Storage::disk('public')->url($this->image) : null;
	}

	public function getThumbUrlAttribute(): ?string
	{
		return $this->thumb ? Storage::disk('public')->url($this->thumb) : null;
	}
}

