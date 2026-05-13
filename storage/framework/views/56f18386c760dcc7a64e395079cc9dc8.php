

<?php $__env->startSection('title', 'Editar Contrato'); ?>
<?php $__env->startSection('page-title', 'Editar Contrato'); ?>
<?php $__env->startSection('page-description', 'Actualizar información del contrato de servicios TI'); ?>

<?php $__env->startSection('content'); ?>

<?php if(!isset($contrato) || !$contrato): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-red-800 mb-2">Error: Contrato no encontrado</h2>
        <p class="text-red-700 mb-4">No se pudo cargar el contrato para editar.</p>
        <a href="<?php echo e(route('parametros.contratos.index')); ?>" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
            Volver a la lista
        </a>
    </div>
<?php else: ?>
<div class="max-w-4xl">
    <form action="<?php echo e(url('parametros/contratos/' . $contrato->id)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Información Básica</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente *</label>
                    <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione cliente</option>
                        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cliente->id); ?>" <?php echo e(old('cliente_id', $contrato->cliente_id) == $cliente->id ? 'selected' : ''); ?>>
                                <?php echo e($cliente->razon_social); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Número de Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Contrato *</label>
                    <input type="text" name="numero_contrato" value="<?php echo e(old('numero_contrato', $contrato->numero_contrato)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['numero_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="Ej: CT-2026-001">
                    <?php $__errorArgs = ['numero_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Tipo de Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Contrato *</label>
                    <select name="tipo_contrato" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['tipo_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione tipo</option>
                        <option value="SOPORTE_TI" <?php echo e(old('tipo_contrato', $contrato->tipo_contrato) == 'SOPORTE_TI' ? 'selected' : ''); ?>>Soporte TI</option>
                        <option value="MANTENIMIENTO" <?php echo e(old('tipo_contrato', $contrato->tipo_contrato) == 'MANTENIMIENTO' ? 'selected' : ''); ?>>Mantenimiento</option>
                        <option value="INFRAESTRUCTURA" <?php echo e(old('tipo_contrato', $contrato->tipo_contrato) == 'INFRAESTRUCTURA' ? 'selected' : ''); ?>>Infraestructura</option>
                        <option value="CONSULTORIA" <?php echo e(old('tipo_contrato', $contrato->tipo_contrato) == 'CONSULTORIA' ? 'selected' : ''); ?>>Consultoría</option>
                    </select>
                    <?php $__errorArgs = ['tipo_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Modalidad -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modalidad *</label>
                    <select name="modalidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['modalidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione modalidad</option>
                        <option value="MENSUAL" <?php echo e(old('modalidad', $contrato->modalidad) == 'MENSUAL' ? 'selected' : ''); ?>>Mensual</option>
                        <option value="TRIMESTRAL" <?php echo e(old('modalidad', $contrato->modalidad) == 'TRIMESTRAL' ? 'selected' : ''); ?>>Trimestral</option>
                        <option value="SEMESTRAL" <?php echo e(old('modalidad', $contrato->modalidad) == 'SEMESTRAL' ? 'selected' : ''); ?>>Semestral</option>
                        <option value="ANUAL" <?php echo e(old('modalidad', $contrato->modalidad) == 'ANUAL' ? 'selected' : ''); ?>>Anual</option>
                    </select>
                    <?php $__errorArgs = ['modalidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Fechas y Valores -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Fechas y Valores</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Fecha de Inicio -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" value="<?php echo e(old('fecha_inicio', $contrato->fecha_inicio?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['fecha_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['fecha_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Fin Prevista *</label>
                    <input type="date" name="fecha_fin" value="<?php echo e(old('fecha_fin', $contrato->fecha_fin?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['fecha_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['fecha_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Fecha de Firma -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Firma *</label>
                    <input type="date" name="fecha_firma" value="<?php echo e(old('fecha_firma', $contrato->fecha_firma?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['fecha_firma'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['fecha_firma'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Valor del Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor del Contrato *</label>
                    <input type="number" name="valor_contrato" value="<?php echo e(old('valor_contrato', $contrato->valor_contrato)); ?>" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['valor_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="0.00">
                    <?php $__errorArgs = ['valor_contrato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Moneda -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Moneda *</label>
                    <select name="moneda" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['moneda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione moneda</option>
                        <option value="COP" <?php echo e(old('moneda', $contrato->moneda) == 'COP' ? 'selected' : ''); ?>>COP (Pesos Colombianos)</option>
                        <option value="USD" <?php echo e(old('moneda', $contrato->moneda) == 'USD' ? 'selected' : ''); ?>>USD (Dólares Estadounidenses)</option>
                        <option value="EUR" <?php echo e(old('moneda', $contrato->moneda) == 'EUR' ? 'selected' : ''); ?>>EUR (Euros)</option>
                    </select>
                    <?php $__errorArgs = ['moneda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Detalles del Contrato -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Detalles del Contrato</h3>
            
            <!-- Alcance de Servicios -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alcance de Servicios</label>
                <textarea name="alcance_servicios" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['alcance_servicios'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Descripción detallada del alcance..."><?php echo e(old('alcance_servicios', $contrato->alcance_servicios)); ?></textarea>
                <?php $__errorArgs = ['alcance_servicios'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Condiciones de Pago -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Condiciones de Pago</label>
                <textarea name="condiciones_pago" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['condiciones_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Términos de pago..."><?php echo e(old('condiciones_pago', $contrato->condiciones_pago)); ?></textarea>
                <?php $__errorArgs = ['condiciones_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Cláusulas Especiales -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cláusulas Especiales</label>
                <textarea name="clausulas_especiales" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['clausulas_especiales'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Cláusulas especiales..."><?php echo e(old('clausulas_especiales', $contrato->clausulas_especiales)); ?></textarea>
                <?php $__errorArgs = ['clausulas_especiales'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Estado y Opciones -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Estado y Opciones</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Estado -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado del Contrato *</label>
                    <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione estado</option>
                        <option value="BORRADOR" <?php echo e(old('estado', $contrato->estado) == 'BORRADOR' ? 'selected' : ''); ?>>Borrador</option>
                        <option value="ACTIVO" <?php echo e(old('estado', $contrato->estado) == 'ACTIVO' ? 'selected' : ''); ?>>Activo</option>
                        <option value="VENCIDO" <?php echo e(old('estado', $contrato->estado) == 'VENCIDO' ? 'selected' : ''); ?>>Vencido</option>
                        <option value="TERMINADO" <?php echo e(old('estado', $contrato->estado) == 'TERMINADO' ? 'selected' : ''); ?>>Terminado</option>
                        <option value="RENOVADO" <?php echo e(old('estado', $contrato->estado) == 'RENOVADO' ? 'selected' : ''); ?>>Renovado</option>
                    </select>
                    <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Documento Firmado -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="documento_firmado" value="1" <?php echo e(old('documento_firmado', $contrato->documento_firmado) ? 'checked' : ''); ?> class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Documento Firmado</span>
                    </label>
                </div>

                <!-- Renovación Automática -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="renovacion_automatica" value="1" <?php echo e(old('renovacion_automatica', $contrato->renovacion_automatica) ? 'checked' : ''); ?> class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Renovación Automática</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-3 justify-end flex-wrap">
            <a href="<?php echo e(route('parametros.contratos.index')); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </div>

        <!-- Errores Generales -->
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Se encontraron errores:</p>
                <ul class="list-disc list-inside text-sm text-red-600">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/contratos/edit.blade.php ENDPATH**/ ?>