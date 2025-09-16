<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerContoller extends Controller
{
	public function index()
	{
		$customers = User::withTrashed()
			->where('role', 'customer')
			->with('parentAdmin:id,name,phone')
			->latest()
			->get();

		return view('admin.customers.index', compact('customers'));
	}
}
