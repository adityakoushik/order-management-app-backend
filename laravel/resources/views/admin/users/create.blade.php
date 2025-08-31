@extends('layouts.app')

@section('content')
	<div class="container mx-auto p-6">
		<h1 class="text-2xl font-bold mb-4">Create User</h1>

		@if ($errors->any())
			<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
				<ul>
					@foreach ($errors->all() as $error)
						<li>- {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 max-w-md">
			@csrf
			<div>
				<label class="block mb-1 font-semibold">Name</label>
				<input type="text" name="name" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-semibold">Phone</label>
				<input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-semibold">Password</label>
				<input type="password" name="password" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-semibold">Role</label>
				<select name="role" class="w-full border rounded px-3 py-2" required>
					<option value="admin">Admin</option>
					<option value="customer">Customer</option>
				</select>
			</div>
			<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Create User</button>
		</form>
	</div>
@endsection