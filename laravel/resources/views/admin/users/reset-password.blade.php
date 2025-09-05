@extends('layouts.app')

@section('content')
	<!-- Page Header -->
	<div class="mb-8 bg-gray-50 border-b border-gray-200 px-6 py-3 rounded-t-lg flex items-center justify-between">
		<h1 class="text-xl font-semibold text-gray-800">Reset Password for {{ $user->name }}</h1>

		<a href="{{ route('admin.users.index') }}"
			class="flex items-center gap-3 px-4 py-2 text-base font-semibold bg-blue-50 text-blue-700 border-r-4 border-l-4 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg">
			← Back
		</a>
	</div>

	<div class="container mx-auto">

		<!-- Form Card -->
		<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">

			<form action="{{ route('admin.users.resetPassword.post', $user->id) }}" method="POST" class="space-y-2">
				@csrf
				<div class="mb-4">
					<label for="password" class="block mb-2 text-sm font-semibold text-gray-700">New Password</label>
					<input type="password" name="password" id="password"
						class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
						required>
				</div>
				<div class="mb-4">
					<label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-700">Confirm
						Password</label>
					<input type="password" name="password_confirmation" id="password_confirmation"
						class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
						required>
				</div>
				<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Reset Password</button>
			</form>
		</div>
	</div>
@endsection