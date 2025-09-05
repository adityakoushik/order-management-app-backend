@props([
	'active' => false,
	'href' => '#',
	'icon' => null
])


@php
	$classes = $active
		? 'flex items-center gap-3 px-4 py-2 text-base font-semibold bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-sm hover:bg-blue-100 transition rounded-lg'
		: 'flex items-center gap-3 px-4 py-2 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 transition rounded-lg';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes, 'rel' => 'nofollow']) }}>
	@if($icon)
		<i class="{{ $icon }} text-lg"></i>
	@endif
	<span>{{ $slot }}</span>
</a>
