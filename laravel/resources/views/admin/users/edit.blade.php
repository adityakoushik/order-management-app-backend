@extends('layouts.app')

@section('content')
	<!-- Page Header -->
	<div class="mb-8 bg-gray-50 border-b border-gray-200 px-6 py-3 rounded-t-lg flex items-center justify-between">
		<h1 class="text-xl font-semibold text-gray-800">Edit User</h1>

		<a href="{{ route('admin.users.index') }}"
			class="flex items-center gap-3 px-4 py-2 text-base font-semibold bg-blue-50 text-blue-700 border-r-4 border-l-4 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg">
			← Back
		</a>
	</div>
	<div class="container mx-auto">

		<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
			{{-- Validation Error --}}
			@if ($errors->any())
				<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
					<ul>
						@foreach ($errors->all() as $error)
							<li>- {{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-2">
				@csrf
				@method('PUT')
				<div class="flex items-center justify-between gap-1">
					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Name</label>
						<input type="text" name="name" value="{{ $user->name }}"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							required>
					</div>
					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Phone</label>
						<input type="text" name="phone" value="{{ $user->phone }}"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							required>
					</div>
				</div>

				<div>
					<label class="block mb-2 text-sm font-semibold text-gray-700">Password (Leave blank to keep current)</label>
					<input type="password" name="password"
						class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
				</div>
				<div class="flex items-center justify-between gap-1">

					<div class="w-full">
						<label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
						<select name="role"
							class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
							required>
							<option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
							<option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
						</select>
					</div>

					<div class="w-full">
						<!-- Assign Admin (only if role is customer) -->
						<div id="assignAdminDiv" style="{{ $user->role === 'customer' ? 'block' : 'none' }}">
							<label class="block mb-2 text-sm font-semibold text-gray-700">Assign Admin</label>
							<select name="parent_admin_id"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
								<option value="">-- Select Admin --</option>
								@foreach($admins as $admin)
									<option value="{{ $admin->id }}" {{ $user->parent_admin_id == $admin->id ? 'selected' : '' }}>
										{{ $admin->name }}
									</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>




				<div class="flex space-x-2 items-center justify-start">
					<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
					<a href="{{route('admin.users.index')}}" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</a>
				</div>

			</form>

		</div>




	</div>

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