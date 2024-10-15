@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-perf-green focus:ring-perf-green   rounded-[25px]  shadow-sm',
]) !!}>
