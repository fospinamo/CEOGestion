@props(['icon' => 'fa-inbox', 'message' => 'No hay registros'])

<div class="text-center py-12 text-gray-500">
    <i class="fas {{ $icon }} text-4xl mb-3 opacity-30"></i>
    <p>{{ $message }}</p>
</div>
