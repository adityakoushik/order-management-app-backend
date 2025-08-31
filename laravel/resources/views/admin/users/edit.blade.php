@extends('layouts.app')

@section('content')
	<div class="container mx-auto p-6">
		<h1 class="text-2xl font-bold mb-4">Edit User</h1>

		@if ($errors->any())
			<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
				<ul>
					@foreach ($errors->all() as $error)
						<li>- {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4 max-w-md">
			@csrf
			@method('PUT')
			<div>
				<label class="block mb-1 font-semibold">Name</label>
				<input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-semibold">Phone</label>
				<input type="text" name="phone" value="{{ $user->phone }}" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-semibold">Password (Leave blank to keep current)</label>
				<input type="password" name="password" class="w-full border rounded px-3 py-2">
			</div>
			<div>
				<label class="block mb-1 font-semibold">Role</label>
				<select name="role" class="w-full border rounded px-3 py-2" required>
					<option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
					<option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
				</select>
			</div>
			<div class="flex space-x-2 items-center justify-start">
				<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
				<a href="{{route('admin.users.index')}}" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</a>
			</div>

		</form>
	</div>
@endsection