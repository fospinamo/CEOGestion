

<?php $__env->startSection('page-title', 'Detalles del Usuario'); ?>
<?php $__env->startSection('page-description', 'Información del usuario'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800"><?php echo e($usuario->name); ?></h1>
                <p class="text-gray-600 mt-2"><?php echo e($usuario->email); ?></p>
            </div>
            <?php if(auth()->user()->hasPermission('usuarios.editar')): ?>
                <a href="<?php echo e(route('seguridad.usuarios.edit', $usuario)); ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-600">Rol</p>
                <p class="text-lg font-semibold text-blue-600"><?php echo e($usuario->role?->name ?? 'Sin asignar'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Empresa</p>
                <p class="text-lg font-semibold"><?php echo e($usuario->empresa?->nombre ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Sede</p>
                <p class="text-lg font-semibold"><?php echo e($usuario->sede?->nombre ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Estado</p>
                <?php if($usuario->estado): ?>
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold inline-block">Activo</span>
                <?php else: ?>
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-semibold inline-block">Inactivo</span>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm text-gray-600">Cédula</p>
                <p class="text-lg font-semibold"><?php echo e($usuario->cedula ?? '-'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Teléfono</p>
                <p class="text-lg font-semibold"><?php echo e($usuario->telefono ?? '-'); ?></p>
            </div>
        </div>

        <div class="flex gap-2 pt-4 border-t">
            <a href="<?php echo e(route('seguridad.usuarios.index')); ?>" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                Volver a lista
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/seguridad/usuarios/show.blade.php ENDPATH**/ ?>