<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

	public function registerCustomer(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
			'phone' => 'required|string|regex:/^[0-9]{10}$/|unique:users',
			'password' => 'required|string|min:6',
			'referral_code' => 'required|string|exists:users,referral_code',
		]);

		// Find admin by referral code
		$admin = User::where('referral_code', $request->referral_code)
			->where('role', 'admin')
			->firstOrFail();

		$customer = User::create([
			'name' => $request->name,
			'phone' => $request->phone,
			'password' => Hash::make($request->password),
			'role' => 'customer',
			'parent_admin_id' => $admin->id,
		]);

		$token = $customer->createToken('mobile-app-token')->plainTextToken;

		return response()->json([
			'message' => 'Customer registered successfully',
			'user' => $customer,
			'token' => $token,
		]);
	}


	public function login(Request $request)
	{

		$request->validate([
			'phone' => 'required',
			'password' => 'required|string|min:6',
		]);

		$user = User::where('phone', $request->phone)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			return response()->json(['message' => 'Invalid credentials'], 401);
		}

		// token create
		$token = $user->createToken('mobile-app-token')->plainTextToken;

		return response()->json([
			'user' => $user,
			'token' => $token,
		]);

	}

	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();

		return response()->json(['message' => 'Logged out']);
	}
}
