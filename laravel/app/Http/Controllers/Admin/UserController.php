<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function index()
	{
		// Logic to list users
		$users = User::all();

		return view('admin.users.index', compact('users'));
	}

	public function create()
	{
		// GET admin data
		$admins = User::where('role', 'admin')->get();
		// Logic to show user creation form
		return view('admin.users.create', compact('admins'));
	}

	public function store(Request $request)
	{
		// Logic to store a new user
		$request->validate([
			'name' => 'required|string|max:255',
			'phone' => 'required|string|max:20|unique:users',
			'password' => 'required|string|min:6',
			'role' => 'required|in:admin,customer'
		]);

		$referralCode = null;
		if ($request->role === 'admin') {

			$referralCode = 'OMS' . strtoupper(bin2hex(random_bytes(4))); // 8 hex chars

		}

		User::create([
			'name' => $request->name,
			'phone' => $request->phone,
			'password' => Hash::make($request->password),
			'role' => $request->role,
			'parent_admin_id' => $request->role === 'customer' ? $request->parent_admin_id : null,
			'referral_code' => $referralCode,
		]);

		return redirect()->route('admin.users.index')->with('success', 'User created successfully.');

	}

	public function edit(User $user)
	{
		$admins = User::where('role', 'admin')->get();
		return view('admin.users.edit', compact('user', 'admins'));
	}

	public function update(Request $request, User $user)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
			'password' => 'nullable|string|min:6',
			'role' => 'required|in:admin,customer'
		]);

		$user->name = $request->name;
		$user->phone = $request->phone;
		if ($request->password) {
			$user->password = Hash::make($request->password);
		}
		$user->role = $request->role;
		$user->parent_admin_id = $request->role === 'customer' ? $request->parent_admin_id : null;
		$user->save();

		return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
	}

	public function destroy(User $user)
	{
		$user->delete();
		return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
	}

	public function showResetPasswordForm(User $user)
	{
		return view('admin.users.reset-password', compact('user'));
	}

	public function resetPassword(Request $request, User $user)
	{
		$request->validate([
			'password' => 'required|string|min:6|confirmed',
		]);

		$user->password = Hash::make($request->password);
		$user->save();

		return redirect()->route('admin.users.index')->with('success', 'Password reset successfully for user: ' . $user->name);
	}

}
