@props(['variant' => 'gray', 'size' => 'sm'])
@php
$colors = [
    'green'  => 'bg-green-100 text-green-800',
    'red'    => 'bg-red-100 text-red-800',
    'blue'   => 'bg-blue-100 text-blue-800',
    'yellow' => 'bg-yellow-100 text-yellow-800',
    'purple' => 'bg-purple-100 text-purple-800',
    'gray'   => 'bg-gray-100 text-gray-800',
];
$sizes = [
    'xs' => 'px-2 py-0.5 text-xs',
    'sm' => 'px-3 py-1 text-sm',
];
@endphp
<span {{ $attributes->merge(['class' => "inline-block {$sizes[$size]} font-semibold rounded-full {$colors[$variant]}"]) }}>
    {{ $slot }}
</span>
