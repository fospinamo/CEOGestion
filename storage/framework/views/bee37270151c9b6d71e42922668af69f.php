<?php if(isset($equipo) && !$equipo && request()->method() === 'PUT'): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-red-800 mb-2">Error: Equipo no encontrado</h2>
        <p class="text-red-700 mb-4">No se pudo cargar el equipo para editar.</p>
        <a href="<?php echo e(route('parametros.equipos.index')); ?>" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
            Volver a la lista
        </a>
    </div>
<?php else: ?>
<div class="max-w-4xl">
    <form action="<?php echo e($equipo ? url('parametros/equipos/' . $equipo->id) : route('parametros.equipos.store')); ?>" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php if($equipo): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <!-- Ubicación - Propietario y Sedes -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa</label>
                    <select id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Selecciona una empresa --</option>
                        <?php $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($empresa->id); ?>" <?php echo e(old('empresa_id', $equipo?->area?->sede?->empresa_id ?? '') == $empresa->id ? 'selected' : ''); ?>>
                                <?php echo e($empresa->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente *</label>
                    <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Selecciona un cliente --</option>
                        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cliente->id); ?>" <?php echo e(old('cliente_id', $equipo?->cliente_id ?? $equipo?->area?->sede?->cliente_id ?? '') == $cliente->id ? 'selected' : ''); ?>>
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
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                    <select id="sede_id" name="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['sede_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Seleccione sede</option>
                        <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sede->id); ?>" 
                                data-empresa-id="<?php echo e($sede->empresa_id); ?>"
                                data-cliente-id="<?php echo e($sede->cliente_id); ?>"
                                <?php echo e(old('sede_id', $equipo?->sede_id ?? $equipo?->area?->sede_id ?? '') == $sede->id ? 'selected' : ''); ?>>
                                <?php echo e($sede->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['sede_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Área *</label>
                    <select id="area_id" name="area_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione área</option>
                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($area->id); ?>" 
                                data-sede-id="<?php echo e($area->sede_id); ?>"
                                <?php echo e(old('area_id', $equipo?->area_id ?? '') == $area->id ? 'selected' : ''); ?>>
                                <?php echo e($area->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Equipo *</label>
                    <select name="tipo_equipo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['tipo_equipo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione tipo</option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tipo->id); ?>" <?php echo e(old('tipo_equipo_id', $equipo->tipo_equipo_id ?? '') == $tipo->id ? 'selected' : ''); ?>>
                                <?php echo e($tipo->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['tipo_equipo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contrato de Servicios</label>
                    <select id="contrato_id" name="contrato_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['contrato_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Sin contrato --</option>
                        <?php $__currentLoopData = $contratos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contrato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($contrato->id); ?>" 
                                data-cliente-id="<?php echo e($contrato->cliente_id); ?>"
                                data-numero="<?php echo e($contrato->numero_contrato); ?>"
                                <?php echo e(old('contrato_id', $equipo?->contrato_id ?? '') == $contrato->id ? 'selected' : ''); ?>>
                                <?php echo e($contrato->numero_contrato); ?> - <?php echo e($contrato->cliente->razon_social); ?> (<?php echo e($contrato->tipo_contrato); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['contrato_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-gray-500 text-xs mt-1">Solo se muestran contratos activos</p>
                </div>
            </div>
        </div>

        <!-- Identificación -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Identificación</h3>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Código Activo Cliente *</label>
                    <input type="text" name="codigo_activo_cliente" value="<?php echo e(old('codigo_activo_cliente', $equipo->codigo_activo_cliente ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['codigo_activo_cliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ej: ACT-001" required>
                    <?php $__errorArgs = ['codigo_activo_cliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-gray-500 text-xs mt-1">Código único del cliente para este activo</p>
                </div>

                <div>
                    <?php $__errorArgs = ['serial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-gray-500 text-xs mt-1">Número de serie (debe ser único)</p>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Serial *</label>
                    <input type="text" name="serial" value="<?php echo e(old('serial', $equipo->serial ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['serial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado Operativo *</label>
                    <select name="estado_operativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['estado_operativo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="OPERATIVO" <?php echo e(old('estado_operativo', $equipo->estado_operativo ?? 'OPERATIVO') == 'OPERATIVO' ? 'selected' : ''); ?>>Operativo</option>
                        <option value="MANTENIMIENTO" <?php echo e(old('estado_operativo', $equipo->estado_operativo ?? '') == 'MANTENIMIENTO' ? 'selected' : ''); ?>>Mantenimiento</option>
                        <option value="REPARACION" <?php echo e(old('estado_operativo', $equipo->estado_operativo ?? '') == 'REPARACION' ? 'selected' : ''); ?>>Reparación</option>
                        <option value="BAJA" <?php echo e(old('estado_operativo', $equipo->estado_operativo ?? '') == 'BAJA' ? 'selected' : ''); ?>>Baja</option>
                        <option value="OBSOLETO" <?php echo e(old('estado_operativo', $equipo->estado_operativo ?? '') == 'OBSOLETO' ? 'selected' : ''); ?>>Obsoleto</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Especificaciones -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Especificaciones</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Marca *</label>
                    <select name="marca_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['marca_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- Seleccione marca --</option>
                        <?php $__currentLoopData = $marcas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marca): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($marca->id); ?>" <?php echo e(old('marca_id', $equipo->marca_id ?? '') == $marca->id ? 'selected' : ''); ?>>
                                <?php echo e($marca->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['marca_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modelo *</label>
                    <input type="text" name="modelo" value="<?php echo e(old('modelo', $equipo->modelo ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 <?php $__errorArgs = ['modelo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['modelo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" placeholder="Descripción detallada del equipo, características especiales, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo e(old('descripcion', $equipo->descripcion ?? '')); ?></textarea>
                    <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Especificaciones (JSON)</label>
                    <textarea name="especificaciones_tecnicas" rows="2" placeholder='{"ram":"8GB","procesador":"Intel i5"}' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-mono text-sm"><?php echo e(old('especificaciones_tecnicas', $equipo?->especificaciones_tecnicas ? json_encode($equipo->especificaciones_tecnicas, JSON_PRETTY_PRINT) : '')); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Usuario Asignado</label>
                    <input type="text" name="usuario_asignado" value="<?php echo e(old('usuario_asignado', $equipo->usuario_asignado ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Red -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuración de Red</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IP</label>
                    <input type="text" name="ip_asignada" value="<?php echo e(old('ip_asignada', $equipo->ip_asignada ?? '')); ?>" placeholder="192.168.1.100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">MAC Address</label>
                    <input type="text" name="mac_address" value="<?php echo e(old('mac_address', $equipo->mac_address ?? '')); ?>" placeholder="00:1A:2B:3C:4D:5E" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Fechas y Valor -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Compra</h3>
            
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Compra</label>
                    <input type="date" name="fecha_compra" value="<?php echo e(old('fecha_compra', $equipo?->fecha_compra?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Instalación</label>
                    <input type="date" name="fecha_instalacion" value="<?php echo e(old('fecha_instalacion', $equipo?->fecha_instalacion?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Garantía</label>
                    <input type="date" name="fecha_garantia" value="<?php echo e(old('fecha_garantia', $equipo?->fecha_garantia?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor Compra</label>
                    <input type="number" name="valor_compra" step="0.01" value="<?php echo e(old('valor_compra', $equipo?->valor_compra ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Mantenimiento y Calibración -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mantenimiento & Calibración</h3>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mantenimientos al año</label>
                    <input type="number" name="mantenimientos_anuales" min="0" value="<?php echo e(old('mantenimientos_anuales', $equipo->mantenimientos_anuales ?? 1)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Cuántos mantenimientos debe tener por año</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Calibraciones al año</label>
                    <input type="number" name="calibraciones_anuales" min="0" value="<?php echo e(old('calibraciones_anuales', $equipo->calibraciones_anuales ?? 0)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Cuántas calibraciones debe tener por año</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Último Mantenimiento</label>
                    <input type="date" name="fecha_ultimo_mantenimiento" value="<?php echo e(old('fecha_ultimo_mantenimiento', $equipo?->fecha_ultimo_mantenimiento?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Última Calibración</label>
                    <input type="date" name="fecha_ultima_calibracion" value="<?php echo e(old('fecha_ultima_calibracion', $equipo?->fecha_ultima_calibracion?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Próximo Mantenimiento</label>
                    <input type="date" name="proxima_fecha_mantenimiento" value="<?php echo e(old('proxima_fecha_mantenimiento', $equipo?->proxima_fecha_mantenimiento?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Se calcula automáticamente</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Próxima Calibración</label>
                    <input type="date" name="proxima_fecha_calibracion" value="<?php echo e(old('proxima_fecha_calibracion', $equipo?->proxima_fecha_calibracion?->format('Y-m-d') ?? '')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Se calcula automáticamente</p>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo e(old('observaciones', $equipo->observaciones ?? '')); ?></textarea>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="<?php echo e(route('parametros.equipos.index')); ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <?php echo e($equipo ? 'Actualizar' : 'Registrar'); ?> Equipo
            </button>
        </div>
</div>

<script>
$(document).ready(function() {
    // Almacenar opciones originales
    const sedeOptions = $('#sede_id').html();
    const areaOptions = $('#area_id').html();
    const contratoOptions = $('#contrato_id').html();

    // Preservar selecciones iniciales en edición para no perder contexto guardado.
    const initialSelections = {
        sedeId: $('#sede_id').val(),
        areaId: $('#area_id').val(),
        contratoId: $('#contrato_id').val()
    };
    
    function filterSedes() {
        const empresaId = $('#empresa_id').val();
        const clienteId = $('#cliente_id').val();
        const sedeSelect = $('#sede_id');
        
        // Restaurar opciones originales
        sedeSelect.html(sedeOptions);
        
        // Filtrar opciones
        sedeSelect.find('option').each(function() {
            if ($(this).val() === '') return;

            if (initialSelections.sedeId && $(this).val() == initialSelections.sedeId) {
                return;
            }
            
            const optEmpresaId = $(this).data('empresa-id');
            const optClienteId = $(this).data('cliente-id');
            
            if (empresaId && optEmpresaId == empresaId) {
                // Mostrar
            } else if (clienteId && optClienteId == clienteId) {
                // Mostrar
            } else if (!empresaId && !clienteId) {
                // Sin filtro, mostrar todas
            } else {
                // Ocultar
                $(this).hide();
            }
        });
        
        // Limpiar área y sede si no coinciden
        const selectedSede = sedeSelect.val();
        if (selectedSede && sedeSelect.find('option[value="' + selectedSede + '"]').is(':hidden')) {
            sedeSelect.val('');
        }

        if (initialSelections.sedeId && sedeSelect.find('option[value="' + initialSelections.sedeId + '"]').length) {
            sedeSelect.val(initialSelections.sedeId);
        }
    }
    
    function filterAreas() {
        const sedeId = $('#sede_id').val();
        const areaSelect = $('#area_id');
        
        // Restaurar opciones originales
        areaSelect.html(areaOptions);
        
        // Filtrar opciones
        areaSelect.find('option').each(function() {
            if ($(this).val() === '') return;

            if (initialSelections.areaId && $(this).val() == initialSelections.areaId) {
                return;
            }
            
            const optSedeId = $(this).data('sede-id');
            
            if (sedeId && optSedeId == sedeId) {
                // Mostrar
            } else if (!sedeId) {
                // Sin filtro, mostrar todas
            } else {
                // Ocultar
                $(this).hide();
            }
        });
        
        // Limpiar área si no coincide
        const selectedArea = areaSelect.val();
        if (selectedArea && areaSelect.find('option[value="' + selectedArea + '"]').is(':hidden')) {
            areaSelect.val('');
        }

        if (initialSelections.areaId && areaSelect.find('option[value="' + initialSelections.areaId + '"]').length) {
            areaSelect.val(initialSelections.areaId);
        }
    }

    function filterContratos() {
        const clienteId = $('#cliente_id').val();
        const contratoSelect = $('#contrato_id');
        
        // Restaurar opciones originales
        contratoSelect.html(contratoOptions);
        
        // Si no hay cliente seleccionado, mostrar todos (filtrados solo por estado ACTIVO desde el servidor)
        if (!clienteId) {
            return;
        }
        
        // Filtrar opciones por cliente
        contratoSelect.find('option').each(function() {
            if ($(this).val() === '') return;

            if (initialSelections.contratoId && $(this).val() == initialSelections.contratoId) {
                return;
            }
            
            const optClienteId = $(this).data('cliente-id');
            
            if (optClienteId == clienteId) {
                // Mostrar
            } else {
                // Ocultar
                $(this).hide();
            }
        });
        
        // Limpiar contrato si no coincide
        const selectedContrato = contratoSelect.val();
        if (selectedContrato && contratoSelect.find('option[value="' + selectedContrato + '"]').is(':hidden')) {
            contratoSelect.val('');
        }

        if (initialSelections.contratoId && contratoSelect.find('option[value="' + initialSelections.contratoId + '"]').length) {
            contratoSelect.val(initialSelections.contratoId);
        }
    }
    
    // Eventos
    $('#empresa_id').on('change', function() {
        if ($(this).val()) {
            $('#cliente_id').val('');
        }
        filterSedes();
        filterAreas();
        filterContratos();
    });
    
    $('#cliente_id').on('change', function() {
        if ($(this).val()) {
            $('#empresa_id').val('');
        }
        filterSedes();
        filterAreas();
        filterContratos();
    });
    
    $('#sede_id').on('change', function() {
        filterAreas();
    });
    
    // Filtrar al cargar si hay valores preseleccionados
    filterSedes();
    filterAreas();
    filterContratos();
});
</script>
<?php endif; ?>


<?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/parametros/equipos/form.blade.php ENDPATH**/ ?>