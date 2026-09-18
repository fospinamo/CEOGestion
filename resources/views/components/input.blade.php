@props(['type' => 'text', 'name', 'label' => null, 'error' => false, 'required' => false, 'placeholder' => ''])

@if($label)
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
    </label>
@endif

<input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ old($name) }}"
    placeholder="{{ $placeholder }}"
    @if($required) required @endif
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500'
            . ($error ? ' border-red-500' : '')
    ]) }}
>

@if($error && $errors->has($name))
    <p class="text-red-600 text-sm mt-1">{{ $errors->first($name) }}</p>
@endif
