<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 🔹 Sanctum use korte hobe

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable; // 🔹 ekhane add koro

	protected $fillable = [
		'name',
		'email',
		'phone',
		'role',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}
}
