@extends('layouts.app')

@section('content')
	<div class="container mx-auto p-6">
		<h1 class="text-2xl font-bold mb-4">Reset Password for {{ $user->name }}</h1>
		<form action="{{ route('admin.users.resetPassword.post', $user->id) }}" method="POST"
			class="max-w-md mx-auto bg-white p-6 rounded shadow">
			@csrf
			<div class="mb-4">
				<label for="password" class="block text-gray-700">New Password</label>
				<input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded" required>
			</div>
			<div class="mb-4">
				<label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
				<input type="password" name="password_confirmation" id="password_confirmation"
					class="w-full px-3 py-2 border rounded" required>
			</div>
			<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Reset Password</button>
		</form>
	</div>
@endsection