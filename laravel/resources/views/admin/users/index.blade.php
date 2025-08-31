@extends('layouts.app')

@section('content')
	<div class="container mx-auto p-6">
		<h1 class="text-2xl font-bold mb-4">Users</h1>
		<a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Create
			User</a>

		@if(session('success'))
			<div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
		@endif

		<table class="min-w-full bg-white shadow rounded">
			<thead>
				<tr>
					<th class="py-2 px-4 border-b">ID</th>
					<th class="py-2 px-4 border-b">Name</th>
					<th class="py-2 px-4 border-b">Phone</th>
					<th class="py-2 px-4 border-b">Role</th>
					<th class="py-2 px-4 border-b">Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td class="py-2 px-4 border-b">{{ $user->id }}</td>
						<td class="py-2 px-4 border-b">{{ $user->name }}</td>
						<td class="py-2 px-4 border-b">{{ $user->phone }}</td>
						<td class="py-2 px-4 border-b capitalize">{{ $user->role }}</td>
						<td class="py-2 px-4 border-b space-x-2">
							<div class="flex space-x-2 items-center justify-start">
								<a href="{{ route('admin.users.edit', $user->id) }}"
									class="bg-blue-500 text-white px-3 py-1 rounded">Edit</a>
								<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
									@csrf
									@method('DELETE')
									<button type="submit" class="bg-red-500 text-white px-3 py-1 rounded"
										onclick="return confirm('Are you sure?')">Delete</button>
								</form>
								@if(auth()->user()->role === 'superadmin')
									<a href="{{ route('admin.users.resetPassword', $user->id) }}"
										class="bg-yellow-500 text-white px-3 py-1 rounded">Reset Password</a>
								@endif
							</div>

						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection