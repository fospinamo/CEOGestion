

<?php $__env->startSection('title', isset($isEdit) && $isEdit ? 'Editar Servicio' : 'Registrar Servicio'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            <?php if(isset($isEdit) && $isEdit): ?>
                ✏️ Editar Servicio #<?php echo e($servicio->id); ?>

            <?php else: ?>
                📋 Registrar Nuevo Servicio
            <?php endif; ?>
        </h1>
        <a href="<?php echo e(route('incidencias.servicios.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            ← Volver
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form id="form-servicio" method="POST" 
            action="<?php if(isset($isEdit) && $isEdit): ?><?php echo e(route('incidencias.servicios.update', $servicio)); ?><?php else: ?><?php echo e(route('incidencias.servicios.store')); ?><?php endif; ?>" 
            enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php if(isset($isEdit) && $isEdit): ?>
                <?php echo method_field('PATCH'); ?>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- SECCIÓN 1: Cliente, Sede, Área y Equipo -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">👤 Información de Ubicación</h3>
                </div>

                <!-- Cliente -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Cliente *</label>
                    <select name="cliente_id" id="cliente_id" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Seleccione un cliente</option>
                        <?php if(isset($clientes) && $clientes->count() > 0): ?>
                            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isSelected = old('cliente_id') == $cliente->id || 
                                                  (isset($isEdit) && $isEdit && $servicio && $servicio->equipo && $servicio->equipo->area->sede->cliente_id == $cliente->id);
                                ?>
                                <option value="<?php echo e($cliente->id); ?>" <?php echo e($isSelected ? 'selected' : ''); ?>>
                                    <?php echo e($cliente->razon_social); ?> (<?php echo e($cliente->documento); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <option value="">No hay clientes disponibles</option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Sede -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Sede *</label>
                    <select name="sede_id" id="sede_id" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['sede_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        <?php if(!old('cliente_id') && !(isset($isEdit) && $isEdit && $servicio && $servicio->equipo)): ?>required disabled <?php endif; ?>>
                        <option value="">Seleccione cliente primero</option>
                        <?php if(isset($sedes) && $sedes->count() > 0): ?>
                            <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isSelected = old('sede_id') == $sede->id || 
                                                  (isset($isEdit) && $isEdit && $servicio && $servicio->equipo && $servicio->equipo->area->sede_id == $sede->id);
                                ?>
                                <option value="<?php echo e($sede->id); ?>" 
                                    data-cliente-id="<?php echo e($sede->cliente_id); ?>"
                                    data-empresa-id="<?php echo e($sede->empresa_id); ?>"
                                    <?php echo e($isSelected ? 'selected' : ''); ?>>
                                    <?php echo e($sede->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['sede_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Área -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Área *</label>
                    <select name="area_id" id="area_id" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        <?php if(!old('sede_id') && !(isset($isEdit) && $isEdit && $servicio && $servicio->equipo)): ?>required disabled <?php endif; ?>>
                        <option value="">Seleccione sede primero</option>
                        <?php if(isset($areas) && $areas->count() > 0): ?>
                            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isSelected = old('area_id') == $area->id || 
                                                  (isset($isEdit) && $isEdit && $servicio && $servicio->equipo && $servicio->equipo->area_id == $area->id);
                                ?>
                                <option value="<?php echo e($area->id); ?>" 
                                    data-sede-id="<?php echo e($area->sede_id); ?>"
                                    <?php echo e($isSelected ? 'selected' : ''); ?>>
                                    <?php echo e($area->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Equipo -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Equipo *</label>
                    <select name="equipo_id" id="equipo_id" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['equipo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required disabled>
                        <option value="">Seleccione área primero</option>
                    </select>
                    <?php $__errorArgs = ['equipo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- SECCIÓN 2: Tipo y Prioridad -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">⚙️ Tipo de Servicio</h3>
                </div>

                <!-- Tipo de Servicio -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Tipo de Servicio *</label>
                    <select name="tipo_servicio" id="tipo_servicio" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['tipo_servicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required disabled>
                        <option value="">Primero seleccione un cliente</option>
                    </select>
                    <?php $__errorArgs = ['tipo_servicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Prioridad -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Prioridad *</label>
                    <select name="prioridad" id="prioridad" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['prioridad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php
                            $currentPrioridad = old('prioridad') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->prioridad : 'MEDIA');
                        ?>
                        <option value="BAJA" <?php echo e($currentPrioridad === 'BAJA' ? 'selected' : ''); ?>>BAJA 🟢</option>
                        <option value="MEDIA" <?php echo e($currentPrioridad === 'MEDIA' ? 'selected' : ''); ?>>MEDIA 🟡</option>
                        <option value="ALTA" <?php echo e($currentPrioridad === 'ALTA' ? 'selected' : ''); ?>>ALTA 🟠</option>
                        <option value="CRITICA" <?php echo e($currentPrioridad === 'CRITICA' ? 'selected' : ''); ?>>CRITICA 🔴</option>
                    </select>
                    <?php $__errorArgs = ['prioridad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- SECCIÓN 3: Información de Contacto -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">📞 Información de Contacto</h3>
                </div>

                <!-- Reportado por -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Reportado por *</label>
                    <input type="text" name="reportado_por" id="reportado_por" 
                        value="<?php echo e(old('reportado_por') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->solicitado_por : '')); ?>" 
                        class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['reportado_por'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['reportado_por'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Teléfono contacto -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Teléfono contacto *</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto" 
                        value="<?php echo e(old('telefono_contacto') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->contacto_solicitante : '')); ?>" 
                        class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['telefono_contacto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['telefono_contacto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Email contacto -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Email contacto</label>
                    <input type="email" name="email_contacto" id="email_contacto" 
                        value="<?php echo e(old('email_contacto') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->email_contacto : '')); ?>" 
                        class="w-full border rounded px-3 py-2">
                </div>

                <!-- SECCIÓN 4: Información del Contrato -->
                <div class="md:col-span-2 mt-4" id="contrato_info"></div>

                <!-- SECCIÓN 5: Descripción del Problema -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">🔍 Descripción del Problema</h3>
                </div>

                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-bold mb-2">Descripción detallada *</label>
                    <textarea name="descripcion_problema" id="descripcion_problema" rows="4" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['descripcion_problema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><?php echo e(old('descripcion_problema') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->descripcion_problema : '')); ?></textarea>
                    <p class="text-gray-500 text-xs mt-1">Describa con detalle el problema reportado por el cliente</p>
                    <?php $__errorArgs = ['descripcion_problema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Observaciones -->
                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-bold mb-2">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('observaciones') ?? (isset($isEdit) && $isEdit && $servicio ? $servicio->observaciones : '')); ?></textarea>
                    <p class="text-gray-500 text-xs mt-1">Información adicional relevante sobre el servicio</p>
                    <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- SECCIÓN 6: Documentos Adjuntos -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">📎 Documentos Adjuntos</h3>
                </div>

                <!-- Carga de archivos -->
                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-bold mb-2">Cargar archivos</label>
                    <input type="file" name="documentos_adjuntos[]" id="documentos_adjuntos" multiple class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['documentos_adjuntos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.txt,.zip">
                    <p class="text-gray-500 text-xs mt-1">
                        Soportes del servicio (PDF, Word, Excel, Imágenes, ZIP). Máximo 5MB por archivo. Opcional.
                    </p>
                    <?php $__errorArgs = ['documentos_adjuntos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Campos ocultos para datos del contrato -->
            <input type="hidden" name="contrato_id" id="contrato_id" value="">
            <input type="hidden" name="sla_respuesta" id="sla_respuesta" value="">
            <input type="hidden" name="sla_solucion" id="sla_solucion" value="">

            <!-- Botones -->
            <div class="flex justify-end space-x-2 mt-6 pt-4 border-t">
                <button type="reset" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">
                    🔄 Limpiar
                </button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition font-bold">
                    <?php if(isset($isEdit) && $isEdit): ?>
                        💾 Actualizar Servicio
                    <?php else: ?>
                        ✅ Registrar Servicio
                    <?php endif; ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Script de verificación - debe ejecutarse ANTES de servicios.js
    console.log('%c=== INICIO DE DIAGNOSTICO ===', 'color: blue; font-weight: bold; font-size: 14px;');
    console.log('Tiempo:', new Date().toLocaleTimeString());
    console.log('jQuery disponible:', typeof jQuery !== 'undefined' ? '✅ SÍ' : '❌ NO');
    if (typeof jQuery !== 'undefined') {
        console.log('jQuery versión:', jQuery.fn.jquery);
        console.log('jQuery noConflict:', typeof jQuery.noConflict);
    }
    console.log('Document ready state:', document.readyState);
    
    // Verificar elementos del formulario
    console.log('%c=== ELEMENTOS DEL FORMULARIO ===', 'color: green; font-weight: bold;');
    console.log('Elemento #cliente_id existe:', document.getElementById('cliente_id') !== null);
    console.log('Elemento #sede_id existe:', document.getElementById('sede_id') !== null);
    console.log('Elemento #area_id existe:', document.getElementById('area_id') !== null);
    console.log('Elemento #equipo_id existe:', document.getElementById('equipo_id') !== null);
    console.log('Elemento #tipo_servicio existe:', document.getElementById('tipo_servicio') !== null);
    console.log('Elemento #prioridad existe:', document.getElementById('prioridad') !== null);
    console.log('Elemento #form-servicio existe:', document.getElementById('form-servicio') !== null);
    console.log('%c=== FIN DE DIAGNOSTICO ===', 'color: blue; font-weight: bold; font-size: 14px;');
</script>
<script src="<?php echo e(asset('js/servicios.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/incidencias/servicios/create.blade.php ENDPATH**/ ?>