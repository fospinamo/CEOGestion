<?php $__env->startSection('title', 'Procesos'); ?>
<?php $__env->startSection('page-title', 'Procesos'); ?>
<?php $__env->startSection('page-description', 'Gestión de procesos por empresa y sede'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Procesos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: <?php echo e($procesos->count()); ?> procesos</p>
        </div>
        <a href="<?php echo e(route('parametros.procesos.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Proceso
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <form method="GET" action="<?php echo e(route('parametros.procesos.index')); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-building text-blue-600"></i> Filtrar por Empresa
                </label>
                <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas las empresas --</option>
                    <?php $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($empresa->id); ?>" <?php echo e(request('empresa_id') == $empresa->id ? 'selected' : ''); ?>>
                            <?php echo e($empresa->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-green-600"></i> Filtrar por Sede
                </label>
                <select name="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas las sedes --</option>
                    <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sede->id); ?>" <?php echo e(request('sede_id') == $sede->id ? 'selected' : ''); ?>>
                            <?php echo e($sede->nombre); ?> - <?php echo e($sede->cliente?->razon_social ?? $sede->empresa?->nombre ?? 'Sin propietario'); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="<?php echo e(route('parametros.procesos.index')); ?>" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaProcesos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Proceso</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subprocesos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rutas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $procesos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-semibold text-gray-900"><?php echo e($proceso->proceso); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-700"><?php echo e($proceso->subprocesos->count()); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-700"><?php echo e($proceso->empresa->nombre); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-700"><?php echo e($proceso->sede->nombre); ?></td>
                        <td class="px-6 py-3 text-sm text-blue-700">
                            <?php echo e($proceso->subprocesos->pluck('ruta')->take(2)->implode(', ')); ?>

                            <?php if($proceso->subprocesos->count() > 2): ?>
                                <span class="text-xs text-gray-500">+<?php echo e($proceso->subprocesos->count() - 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3">
                            <?php
                                $statusStyle = $proceso->estado
                                    ? ['bg' => '#dcfce7', 'text' => '#166534']
                                    : ['bg' => '#fee2e2', 'text' => '#991b1b'];
                            ?>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: <?php echo e($statusStyle['bg']); ?>; color: <?php echo e($statusStyle['text']); ?>;">
                                <?php echo e($proceso->estado ? 'Activo' : 'Inactivo'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?php echo e(route('parametros.procesos.show', ['proceso' => $proceso->id])); ?>" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                <a href="<?php echo e(route('parametros.procesos.edit', ['proceso' => $proceso->id])); ?>" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                <form action="<?php echo e(route('parametros.procesos.destroy', ['proceso' => $proceso->id])); ?>" method="POST" onsubmit="return confirm('¿Eliminar este proceso?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No hay procesos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/parametros/procesos/index.blade.php ENDPATH**/ ?>