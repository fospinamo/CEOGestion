

<?php $__env->startSection('title', 'Mi Dashboard - ' . auth()->user()->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">👨‍💼 Mi Dashboard de Servicios</h1>
        <p class="text-gray-600">Bienvenido <strong><?php echo e($tecnico->name); ?></strong>, aquí están tus servicios asignados</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total de Servicios -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-semibold">Total Asignados</p>
                    <p class="text-4xl font-bold"><?php echo e($dashboard['servicios_totales']); ?></p>
                </div>
                <i class="fas fa-briefcase text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Servicios Pendientes -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-semibold">Pendientes</p>
                    <p class="text-4xl font-bold"><?php echo e($dashboard['servicios_pendientes']); ?></p>
                </div>
                <i class="fas fa-clock text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-semibold">En Proceso</p>
                    <p class="text-4xl font-bold"><?php echo e($dashboard['servicios_en_proceso']); ?></p>
                </div>
                <i class="fas fa-wrench text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Pendientes Repuesto -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-semibold">Pendientes Repuesto</p>
                    <p class="text-4xl font-bold"><?php echo e($dashboard['servicios_pendientes_repuesto']); ?></p>
                </div>
                <i class="fas fa-box text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Completados -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold">Completados</p>
                    <p class="text-4xl font-bold"><?php echo e($dashboard['servicios_completados']); ?></p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lista de Servicios Pendientes -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-yellow-500 text-white px-6 py-4">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Mis Servicios Pendientes
                    </h2>
                </div>

                <div class="divide-y">
                    <?php if($servicios->where('estado', 'PENDIENTE')->count() > 0): ?>
                        <?php $__currentLoopData = $servicios->where('estado', 'PENDIENTE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-gray-900">Servicio #<?php echo e($servicio->id); ?></p>
                                        <p class="text-sm text-gray-600">
                                            📍 <?php echo e($servicio->equipo?->area?->sede?->cliente->razon_social ?? 'Sin cliente'); ?>

                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                        🟡 <?php echo e($servicio->prioridad); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2"><?php echo e(substr($servicio->descripcion_problema, 0, 50)); ?>...</p>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-500">
                                        📅 <?php echo e($servicio->fecha_solicitud->format('d/m/Y H:i')); ?>

                                    </p>
                                    <a href="<?php echo e(route('incidencias.servicios.show', $servicio)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                        Ver Detalles →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500">
                            <p>✅ ¡Excelente! No hay servicios pendientes</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Servicios en Proceso -->
            <?php if($servicios->where('estado', 'EN_PROCESO')->count() > 0): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-6">
                    <div class="bg-orange-500 text-white px-6 py-4">
                        <h2 class="text-xl font-bold flex items-center">
                            <i class="fas fa-spinner mr-2"></i>
                            En Proceso (<?php echo e($servicios->where('estado', 'EN_PROCESO')->count()); ?>)
                        </h2>
                    </div>

                    <div class="divide-y">
                        <?php $__currentLoopData = $servicios->where('estado', 'EN_PROCESO'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900">Servicio #<?php echo e($servicio->id); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($servicio->equipo?->area?->sede?->cliente->razon_social ?? 'Sin cliente'); ?></p>
                                    </div>
                                    <a href="<?php echo e(route('incidencias.servicios.show', $servicio)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                        Continuar →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Información del Técnico -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Mi Información</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nombre</p>
                        <p class="font-semibold text-gray-900"><?php echo e($tecnico->name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900"><?php echo e($tecnico->email); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Teléfono</p>
                        <p class="font-semibold text-gray-900"><?php echo e($tecnico->telefono ?? 'No registrado'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rol</p>
                        <p class="font-semibold text-gray-900"><?php echo e($tecnico->role?->name ?? 'Sin rol'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="<?php echo e(route('incidencias.servicios.mi-panel')); ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-center transition">
                        Ver Mi Panel
                    </a>
                    <a href="<?php echo e(route('incidencias.servicios.index')); ?>" class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded text-center transition">
                        Ver Todos los Servicios
                    </a>
                </div>
            </div>

            <!-- Distribución de Prioridades -->
            <?php if($dashboard['servicios_por_prioridad']): ?>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Por Prioridad</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $dashboard['servicios_por_prioridad']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prioridad => $cantidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600"><?php echo e(ucfirst($prioridad)); ?></span>
                                <span class="font-bold text-lg text-gray-900"><?php echo e($cantidad); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/home/tecnico-dashboard.blade.php ENDPATH**/ ?>