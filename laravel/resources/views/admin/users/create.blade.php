@extends('layouts.app')

@section('content')
	<!-- Page Header -->
	<div class="mb-8 bg-gray-50 border-b border-gray-200 px-6 py-3 rounded-t-lg flex items-center justify-between">
		<h1 class="text-xl font-semibold text-gray-800">Create User</h1>

		<a href="{{ route('admin.users.index') }}"
			class="flex items-center gap-3 px-4 py-2 text-base font-semibold bg-blue-50 text-blue-700 border-r-4 border-l-4 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg">
			← Back
		</a>
	</div>
	<div class="container mx-auto">


		<!-- Form Card -->
		<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
			<!-- Validation Errors -->
			@if ($errors->any())
				<div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg shadow-sm">
					<ul class="space-y-1 list-disc list-inside">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form method="POST" action="{{ route('admin.users.store') }}" class="space-y-2">
				@csrf

				<!-- Name -->
				<div class="flex items-center justify-between gap-1">
					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Full Name</label>
						<input type="text" name="name" value="{{ old('name') }}"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							placeholder="Enter full name" required>
					</div>
					<!-- Phone -->
					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Phone</label>
						<input type="text" name="phone" value="{{ old('phone') }}"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							placeholder="Enter phone number" required>
					</div>
				</div>

				<!-- Password with Toggle -->
				<div class="flex items-center justify-between gap-1">

					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
						<div class="relative">
							<input type="password" name="password" id="createPassword"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
								placeholder="Enter password" required>
							<button type="button" onclick="togglePassword()"
								class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
								<!-- Eye Icon (SVG) -->
								<svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
								</svg>
							</button>
						</div>
					</div>

					<!-- Role -->
					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
						<select name="role"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							required>
							<option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
							<option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
						</select>
					</div>

				</div>

				<!-- Assign Admin (only for customers) -->
				<div id="assignAdminDiv" style="display: none;">
					<label class="block mb-2 text-sm font-semibold text-gray-700">Assign Admin</label>
					<select name="parent_admin_id"
						class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
						<option value="">-- Select Admin --</option>
						@foreach($admins as $admin)
							<option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->phone }})</option>
						@endforeach
					</select>
				</div>

				<!-- Submit -->
				<button type="submit"
					class="w-full bg-gradient-to-r from-blue-500 to-blue-800 text-white px-4 py-2 rounded-lg font-semibold shadow hover:scale-105 transition-transform duration-200">
					Create User
				</button>
			</form>
		</div>
	</div>

	<!-- Password Toggle Script -->
	<script>
		function togglePassword() {
			const passwordField = document.getElementById("createPassword");
			if (passwordField.type === "password") {
				passwordField.type = "text";
			} else {
				passwordField.type = "password";
			}
		}
	</script>
	<script>
		const roleSelect = document.querySelector('[name="role"]');
		const assignAdminDiv = document.getElementById('assignAdminDiv');

		roleSelect.addEventListener('change', function () {
			if (this.value === 'customer') {
				assignAdminDiv.style.display = 'block';
			} else {
				assignAdminDiv.style.display = 'none';
			}
		});
	</script>
@endsection