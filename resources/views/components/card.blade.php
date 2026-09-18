@props(['padding' => true])
<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow' . ($padding ? ' p-6' : '')]) }}>
    {{ $slot }}
</div>
