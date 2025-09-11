@extends('layouts.app')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<h1 class="text-2xl font-bold mb-6">Admin ↔ Customer Mapping</h1>

		<div x-data="{ search: '' }" class="space-y-4">
			{{-- Search bar --}}
			<input type="text" placeholder="Search..." class="w-full border rounded-lg p-2 mb-4" x-model="search">

			{{-- Admin accordion --}}
			@foreach($admins as $admin)
				<div x-data="{ open: false }"
					x-show="{{ json_encode($admin->name . ' ' . $admin->phone . ' ' . $admin->customers->pluck('name')->join(' ')) }}.toLowerCase().includes(search.toLowerCase())"
					class="border rounded-lg shadow bg-white">

					{{-- Admin header --}}
					<div class="flex items-center justify-between p-4 cursor-pointer bg-gray-50 hover:bg-gray-100"
						@click="open = !open">
						<h2 class="font-bold text-lg">👨‍💼 {{ $admin->name }} ({{ $admin->phone }})</h2>
						<span class="text-sm text-gray-600">{{ $admin->customers->count() }} Customers</span>
					</div>

					{{-- Customer list --}}
					<div x-show="open" x-transition class="p-4">
						@if($admin->customers->isEmpty())
							<p class="text-gray-500">No customers assigned</p>
						@else
							<ul class="list-disc ml-6 space-y-1">
								@foreach($admin->customers as $customer)
									<li>{{ $customer->name }} ({{ $customer->phone }})</li>
								@endforeach
							</ul>
						@endif
					</div>
				</div>
			@endforeach
		</div>
	</div>
@endsection