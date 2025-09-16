<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
	public function index(Request $request)
	{
		$user = $request->user();

		if ($user->role !== 'admin') {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		$customers = $user->customers()
			->select('id', 'name', 'phone', 'created_at')
			->orderBy('created_at', 'desc')
			->get();

		return response()->json([
			'referral_code' => $user->referral_code,
			'customers' => $customers,
		]);
	}

	public function destroy($id)
	{
		$customer = User::where('id', $id)->where('role', 'customer')->first();

		if (!$customer) {
			return response()->json(['message' => 'Customer not found'], 404);
		}

		$customer->delete(); // Soft delete 

		return response()->json(['message' => 'Customer deleted successfully']);
	}
}
