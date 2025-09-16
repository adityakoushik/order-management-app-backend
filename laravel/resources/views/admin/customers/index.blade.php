@extends('layouts.app')

@section('content')
	<!-- Header Section -->

	<div class="flex items-center justify-between mb-8 bg-gray-50 border-b border-gray-200 px-6 py-3 rounded-t-lg">
		<h1 class="text-xl font-semibold text-gray-800">Customer Management</h1>

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

						<th class="py-3 px-6 text-left">Name</th>
						<th class="py-3 px-6 text-left">Phone</th>
						<th class="py-3 px-6 text-left">Parent Admin</th>
						<th class="py-3 px-6 text-left">Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach($customers as $customer)
						<tr @if($customer->deleted_at) class="hover:bg-gray-50 transition" @endif>
							<td class="py-3 px-6 border-b">{{ $customer->name }}</td>
							<td class="py-3 px-6 border-b font-medium text-gray-800">{{ $customer->phone }}</td>
							<td class="py-3 px-6 border-b">{{ $customer->parentAdmin->name ?? 'N/A' }}</td>
							<td>
								@if($customer->deleted_at)
									<span class="px-2 py-1 text-xs font-semibold rounded-lg bg-orange-100 text-orange-600">Deleted</span>
								@else
									<span class="px-2 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-600">Active</span>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection