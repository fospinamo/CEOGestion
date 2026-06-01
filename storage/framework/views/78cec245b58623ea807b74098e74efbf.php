

<?php $__env->startSection('title', 'Equipos TI'); ?>
<?php $__env->startSection('page-title', 'Gestión de Equipos'); ?>
<?php $__env->startSection('page-description', 'Registro y control de equipos TI'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Equipos TI</h2>
            <p class="text-gray-600 text-sm mt-1">Total: <?php echo e($equipos->count()); ?> equipos</p>
        </div>
        <a href="<?php echo e(route('parametros.equipos.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Equipo
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <form method="GET" action="<?php echo e(route('parametros.equipos.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Filtro por Empresa -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-building text-blue-600"></i> Empresa
                </label>
                <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas --</option>
                    <?php $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($empresa->id); ?>" <?php echo e(request('empresa_id') == $empresa->id ? 'selected' : ''); ?>>
                            <?php echo e($empresa->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filtro por Cliente -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-green-600"></i> Cliente
                </label>
                <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas --</option>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cliente->id); ?>" <?php echo e(request('cliente_id') == $cliente->id ? 'selected' : ''); ?>>
                            <?php echo e($cliente->razon_social); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filtro por Tipo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-microchip text-purple-600"></i> Tipo
                </label>
                <select name="tipo_equipo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos --</option>
                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tipo->id); ?>" <?php echo e(request('tipo_equipo_id') == $tipo->id ? 'selected' : ''); ?>>
                            <?php echo e($tipo->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-circle text-red-600"></i> Estado
                </label>
                <select name="estado_operativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos --</option>
                    <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($estado); ?>" <?php echo e(request('estado_operativo') == $estado ? 'selected' : ''); ?>>
                            <?php echo e($estado); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="<?php echo e(route('parametros.equipos.index')); ?>" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Botones de Descarga -->
    <div class="flex gap-2">
        <a href="<?php echo e(route('parametros.equipos.exportar.excel', request()->query())); ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Descargar Excel
        </a>
        <a href="<?php echo e(route('parametros.equipos.exportar.pdf', request()->query())); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
        <button id="btnPrintTable" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaEquipos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Marca / Modelo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ubicación</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contrato</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa/Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900"><?php echo e($equipo->codigo_activo_cliente); ?></p>
                            <p class="text-xs text-gray-500">SN: <?php echo e($equipo->serial); ?></p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?php echo e($equipo->tipoEquipo?->nombre ?? 'N/A'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900"><?php echo e($equipo->marca?->nombre ?? 'N/A'); ?> <?php echo e($equipo->modelo); ?></p>
                            <?php if($equipo->descripcion): ?>
                                <p class="text-xs text-gray-600 mt-1 italic truncate" title="<?php echo e($equipo->descripcion); ?>">
                                    <?php echo e(substr($equipo->descripcion, 0, 60)); ?><?php echo e(strlen($equipo->descripcion) > 60 ? '...' : ''); ?>

                                </p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700"><?php echo e($equipo->area?->nombre ?? 'N/A'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($equipo->area?->sede?->nombre ?? 'N/A'); ?></p>
                        </td>
                        <td class="px-6 py-3">
                            <?php if($equipo->contrato): ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <?php echo e($equipo->contrato->numero_contrato); ?>

                                </span>
                                <p class="text-xs text-gray-600 mt-1"><?php echo e(substr($equipo->contrato->tipo_contrato, 0, 15)); ?></p>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">Sin contrato</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900 font-semibold">
                                <?php if($equipo->area?->sede?->cliente): ?>
                                    <i class="fas fa-user text-green-600"></i> <?php echo e($equipo->area->sede->cliente->razon_social); ?>

                                <?php elseif($equipo->area?->sede?->empresa): ?>
                                    <i class="fas fa-building text-blue-600"></i> <?php echo e($equipo->area->sede->empresa->nombre); ?>

                                <?php else: ?>
                                    <span class="text-gray-500">N/A</span>
                                <?php endif; ?>
                            </p>
                        </td>
                        <td class="px-6 py-3">
                            <?php
                                $colorMap = [
                                    'OPERATIVO' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'MANTENIMIENTO' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'REPARACION' => ['bg' => '#fed7aa', 'text' => '#92400e'],
                                    'BAJA' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'OBSOLETO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                ];
                                $colors = $colorMap[$equipo->estado_operativo] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            ?>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: <?php echo e($colors['bg']); ?>; color: <?php echo e($colors['text']); ?>;">
                                <?php echo e($equipo->estado_operativo); ?>

                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?php echo e(route('parametros.equipos.show', $equipo)); ?>" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('parametros.equipos.edit', $equipo)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('parametros.equipos.destroy', $equipo)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-laptop text-3xl mb-2 opacity-50"></i>
                            <p>No hay equipos registrados</p>
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
    const table = $('#tablaEquipos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });

    // Botón de impresión
    $('#btnPrintTable').on('click', function() {
        const printWindow = window.open('', '', 'height=600,width=800');
        const tableHTML = document.querySelector('#tablaEquipos').outerHTML;
        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Equipos TI</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    h1 { text-align: center; color: #333; }
                    .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <h1>Listado de Equipos TI</h1>
                <p>Fecha: ${new Date().toLocaleString()}</p>
                ${tableHTML}
                <div class="footer">
                    <p>Generado por CEOGestion - ${new Date().getFullYear()}</p>
                </div>
            </body>
            </html>
        `;
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.print();
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/parametros/equipos/index.blade.php ENDPATH**/ ?>