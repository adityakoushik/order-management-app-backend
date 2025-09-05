@extends('layouts.app')

@section('content')
	<!-- Header Section -->

	<div class="flex items-center justify-between mb-8 bg-gray-50 border-b border-gray-200 px-6 py-3 rounded-t-lg">
		<h1 class="text-xl font-semibold text-gray-800">User Management</h1>
		<a href="{{ route('admin.users.create') }}"
			class="flex items-center gap-3 px-4 py-2 text-base font-semibold bg-blue-50 text-blue-700 border-r-4 border-l-4 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg">
			Create User
		</a>
	</div>
	<div class="container mx-auto p-6">
		<!-- Success Alert -->
		@if(session('success'))
			<div
				class="mb-6 flex items-center justify-between bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg shadow-sm">
				<span>{{ session('success') }}</span>
				<button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✖</button>
			</div>
		@endif

		<!-- Table Section -->
		<div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-gray-100">
			<table class="min-w-full text-sm text-gray-700">
				<thead class="bg-gray-50 text-gray-600 uppercase text-xs">
					<tr>
						<th class="py-3 px-6 text-left">ID</th>
						<th class="py-3 px-6 text-left">Name</th>
						<th class="py-3 px-6 text-left">Phone</th>
						<th class="py-3 px-6 text-left">Role</th>
						<th class="py-3 px-6 text-left">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr class="hover:bg-gray-50 transition">
							<td class="py-3 px-6 border-b">{{ $user->id }}</td>
							<td class="py-3 px-6 border-b font-medium text-gray-800">{{ $user->name }}</td>
							<td class="py-3 px-6 border-b">{{ $user->phone }}</td>
							<td class="py-3 px-6 border-b capitalize">
								<span
									class="px-2 py-1 text-xs font-semibold rounded-lg
																																																																																																																												{{ $user->role === 'admin' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
									{{ $user->role }}
								</span>
							</td>
							<td class="py-3 px-6 border-b">
								<div class="flex space-x-2">
									<a href="{{ route('admin.users.edit', $user->id) }}"
										class="flex item-center gap-1  px-1 py-0.5 text-base font-semibold bg-blue-50 text-blue-700 border-r-2 border-l-1 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg">
										✏ Edit
									</a>
									<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
										onsubmit="return confirm('Are you sure?')">
										@csrf
										@method('DELETE')
										<button type="submit"
											class="flex item-center gap-1  px-1 py-0.5 text-base font-semibold bg-red-50 text-red-700 border-r-2 border-l-1 border-red-600 shadow-sm hover:bg-red-100 transition rounded-lg">
											🗑 Delete
										</button>
									</form>
									@if(auth()->user()->role === 'superadmin')
										<a href="{{ route('admin.users.resetPassword', $user->id) }}"
											class="flex item-center gap-1  px-1 py-0.5 text-base font-semibold bg-yellow-50 text-yellow-700 border-r-2 border-l-1 border-yellow-600 shadow-sm hover:bg-yellow-100 transition rounded-lg">
											🔑 Reset
										</a>
									@endif
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection