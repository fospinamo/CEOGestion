@props(['variant' => 'primary', 'tag' => 'a'])
@php
$colors = [
    'primary'   => 'bg-blue-600 text-white hover:bg-blue-700',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
    'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
    'success'   => 'bg-green-600 text-white hover:bg-green-700',
    'warning'   => 'bg-yellow-500 text-white hover:bg-yellow-600',
];
@endphp
<{{ $tag }} {{ $attributes->merge(['class' => "inline-flex items-center gap-2 px-4 py-2 rounded-lg transition {$colors[$variant]}"]) }}>
    {{ $slot }}
</{{ $tag }}>
