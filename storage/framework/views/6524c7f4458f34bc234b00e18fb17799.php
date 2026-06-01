
<?php $__env->startSection('title', 'Tipos de Equipos'); ?>
<?php $__env->startSection('page-title', 'Catálogo de Tipos'); ?>
<?php $__env->startSection('page-description', 'Gestión de tipos de equipos disponibles'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tipos de Equipos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: <?php echo e($tipos->count()); ?> tipos</p>
        </div>
        <a href="<?php echo e(route('parametros.tipos-equipos.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Tipo
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaTiposEquipos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Categoría</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Equipos</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900"><?php echo e($tipo->nombre); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($tipo->descripcion ?? 'Sin descripción'); ?></p>
                        </td>
                        <td class="px-6 py-3">
                            <?php if($tipo->categoriaObj): ?>
                                <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full" 
                                    style="background-color: <?php echo e($tipo->categoriaObj->color); ?>20; color: <?php echo e($tipo->categoriaObj->color); ?>;">
                                    <?php if($tipo->categoriaObj->icono): ?>
                                        <i class="fas <?php echo e($tipo->categoriaObj->icono); ?>"></i>
                                    <?php endif; ?>
                                    <?php echo e($tipo->categoriaObj->nombre); ?>

                                </span>
                            <?php else: ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Sin categoría
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                <?php echo e($tipo->equipos_count); ?>

                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?php echo e(route('parametros.tipos-equipos.show', $tipo)); ?>" class="text-blue-600 hover:text-blue-900" title="Ver"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo e(route('parametros.tipos-equipos.edit', $tipo)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Editar"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('parametros.tipos-equipos.destroy', $tipo)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este tipo de equipo?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay tipos de equipos registrados</p>
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
    $('#tablaTiposEquipos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 3 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/parametros/tipos-equipos/index.blade.php ENDPATH**/ ?>