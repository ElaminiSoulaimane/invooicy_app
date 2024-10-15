@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-perf-green text-sm font-medium leading-5 text-perf-green focus:outline-none focus:border-perf-green transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5  text-perf-white	 hover:text-perf-green hover:border-perf-green focus:outline-none focus:text-perf-green focus:border-perf-green transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
