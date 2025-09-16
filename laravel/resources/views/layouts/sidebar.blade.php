<aside x-data="{ open: false }" class="min-h-screen">
	<!-- Fixed left arrow icon for mobile to open sidebar -->
	<button @click="open = true"
		class="fixed z-50 top-16 left-2 bg-white rounded-full shadow p-2 text-gray-800 lg:hidden focus:outline-none">
		<i class="fas fa-arrow-right text-xl"></i>
	</button>

	<!-- Overlay for mobile sidebar -->
	<div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black bg-opacity-40 lg:hidden"
		@click="open = false"></div>

	<!-- Sidebar content -->
	<div :class="{'translate-x-0': open, '-translate-x-full': !open}"
		class="fixed z-50 top-0 left-0 w-64 h-full bg-white shadow-lg border-r transform transition-transform duration-300 lg:static lg:translate-x-0 lg:w-56 lg:block flex flex-col">
		<!-- Close button for mobile -->
		<!-- Collapse button for mobile (hidden for cleaner mobile UI) -->
		<div class="hidden lg:flex items-center justify-between p-4 border-b">
			<span class="text-gray-800 font-bold text-lg">Admin Panel</span>
		</div>
		<!-- Logo/Tagline for desktop (removed 'Admin Panel' label for cleaner UI) -->
		<nav class="flex-1 overflow-y-auto space-y-2 m-2">
			<x-sidebar-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
				icon="fas fa-tachometer-alt">
				Dashboard
			</x-sidebar-nav-link>

			<x-sidebar-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')"
				icon="fas fa-images">
				User Management
			</x-sidebar-nav-link>

			<x-sidebar-nav-link href="{{ route('admin.customer.map') }}" :active="request()->routeIs('admin.customer.map')"
				icon="fas fa-images">
				Admin Customer Mapping
			</x-sidebar-nav-link>

			<x-sidebar-nav-link href="{{ route('admin.customers') }}" :active="request()->routeIs('admin.customers')"
				icon="fas fa-images">
				Customers
			</x-sidebar-nav-link>

		</nav>
	</div>
</aside>
</aside>