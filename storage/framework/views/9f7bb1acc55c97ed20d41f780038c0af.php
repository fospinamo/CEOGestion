

<?php $__env->startSection('page-title', 'Usuarios del Sistema'); ?>
<?php $__env->startSection('page-description', 'Gestión de usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Botón crear usuario -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Usuarios</h1>
        <?php if(auth()->user()->hasPermission('usuarios.crear')): ?>
            <a href="<?php echo e(route('seguridad.usuarios.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Rol</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($usuario->name); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($usuario->email); ?></td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <?php echo e($usuario->role?->name ?? 'Sin rol'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($usuario->empresa?->nombre ?? '-'); ?></td>
                        <td class="px-4 py-3 text-sm">
                            <?php if($usuario->estado): ?>
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm space-x-2 flex">
                            <a href="<?php echo e(route('seguridad.usuarios.show', $usuario)); ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if(auth()->user()->hasPermission('usuarios.editar')): ?>
                                <a href="<?php echo e(route('seguridad.usuarios.edit', $usuario)); ?>" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(auth()->user()->hasPermission('usuarios.eliminar')): ?>
                                <form method="POST" action="<?php echo e(route('seguridad.usuarios.destroy', $usuario)); ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar usuario?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="px-4 py-3 border-t bg-gray-50">
            <?php echo e($usuarios->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/seguridad/usuarios/index.blade.php ENDPATH**/ ?>