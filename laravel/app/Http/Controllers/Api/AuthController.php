<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
