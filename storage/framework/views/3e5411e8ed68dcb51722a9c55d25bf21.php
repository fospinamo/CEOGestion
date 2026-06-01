

<?php $__env->startSection('title', 'Categorías'); ?>
<?php $__env->startSection('page-title', 'Gestión de Categorías'); ?>
<?php $__env->startSection('page-description', 'Tipos de equipos disponibles en el sistema'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header con botón crear -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Categorías</h2>
            <p class="text-gray-600 text-sm mt-1">Total: <?php echo e($categorias->count()); ?> categorías</p>
        </div>
        <a href="<?php echo e(route('parametros.categorias.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <!-- Tabla de Categorías -->
    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="w-full" id="tablaCategorias">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Color</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Icono</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900"><?php echo e($categoria->nombre); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($categoria->slug); ?></p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700"><?php echo e($categoria->descripcion ?? 'N/A'); ?></p>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded" style="background-color: <?php echo e($categoria->color ?? '#000'); ?>; border: 1px solid #ddd;"></div>
                                <span class="text-xs font-mono text-gray-600"><?php echo e($categoria->color); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <?php if($categoria->icono): ?>
                                <div class="flex items-center gap-2">
                                    <i class="fas <?php echo e($categoria->icono); ?>"></i>
                                    <span class="text-xs text-gray-600"><?php echo e($categoria->icono); ?></span>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Sin icono</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3">
                            <?php if($categoria->estado): ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activa</span>
                            <?php else: ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactiva</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2 flex-wrap">
                                <a href="<?php echo e(route('parametros.categorias.edit', $categoria)); ?>" class="text-yellow-600 hover:text-yellow-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('parametros.categorias.destroy', $categoria)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar categoría?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay categorías registradas</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable si aún no está inicializado
    if (!$.fn.DataTable.isDataTable('#tablaCategorias')) {
        $('#tablaCategorias').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true,
            "pageLength": 10
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/categorias/index.blade.php ENDPATH**/ ?>