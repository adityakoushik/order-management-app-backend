<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerMapController extends Controller
{
	public function index()
	{
		$admins = User::with('customers')->where('role', 'admin')->get();
		// Logic to list admin-customer mappings
		return view('admin.admin_customer_map.index', compact('admins'));
	}
}
