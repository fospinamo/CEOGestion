<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe Servicio Tecnico - Servicio <?php echo e($servicio->id); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #111;
            background: #fff;
        }

        .page {
            padding: 12px 14px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .header .cell {
            display: table-cell;
            vertical-align: middle;
        }

        .header .cell.left {
            width: 25%;
        }

        .header .cell.center {
            width: 50%;
            text-align: center;
        }

        .header .cell.right {
            width: 25%;
            text-align: right;
            font-size: 8px;
        }

        .logo {
            height: 34px;
            width: auto;
        }

        .title {
            font-weight: bold;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        .line {
            border-top: 1px solid #000;
            height: 0;
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: middle;
        }

        .table th {
            font-weight: bold;
            text-align: center;
            background: #f2f2f2;
        }

        .label {
            font-weight: bold;
            font-size: 8px;
        }

        .value {
            font-size: 9px;
        }

        .section-title {
            font-weight: bold;
            text-align: center;
            border: 1px solid #000;
            padding: 3px 4px;
            margin-top: 4px;
            background: #f2f2f2;
        }

        .box {
            border: 1px solid #000;
            padding: 4px;
            min-height: 26px;
            margin-bottom: 4px;
        }

        .box.small {
            min-height: 20px;
        }

        .grid-2 {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .grid-2 .cell {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .grid-2 .cell + .cell {
            border-left: 1px solid #000;
        }

        .radio {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
        }

        .radio.checked {
            background: #000;
        }

        .checkbox {
            display: inline-block;
            width: 7px;
            height: 7px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background: #000;
        }

        .signature {
            border-top: 1px solid #000;
            margin-top: 18px;
            text-align: center;
            padding-top: 2px;
        }

        .signature img {
            max-height: 60px;
            display: block;
            margin: 6px auto 0;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            margin-top: 6px;
        }

        .muted {
            color: #333;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="cell left">
                <?php if(!empty($empresaLogoBase64)): ?>
                    <img src="<?php echo e($empresaLogoBase64); ?>" alt="Logo" class="logo">
                <?php elseif(!empty($empresaLogoPath) && file_exists($empresaLogoPath)): ?>
                    <img src="<?php echo e($empresaLogoPath); ?>" alt="Logo" class="logo">
                <?php endif; ?>
            </div>
            <div class="cell center">
                <div class="title">INFORME TECNICO</div>
            </div>
            <div class="cell right">
                <div><span class="label">No. Informe</span></div>
                <div class="value"><?php echo e($servicio->id); ?></div>
            </div>
        </div>

        <table class="table">
            <tr>
                <td style="width: 18%"><span class="label">FECHA REPORTE INCIDENCIA</span></td>
                <td style="width: 17%" class="value"><?php echo e($servicio->fecha_solicitud->format('d/m/Y')); ?></td>
                <td style="width: 18%"><span class="label">FECHA ATENCION (INFORME)</span></td>
                <td style="width: 17%" class="value"><?php echo e($servicio->fecha_atencion?->format('d/m/Y') ?? 'N/A'); ?></td>
                <td style="width: 15%"><span class="label">HORA INICIO</span></td>
                <td style="width: 15%" class="value"><?php echo e($servicio->hora_inicio_atencion ? \Carbon\Carbon::parse($servicio->hora_inicio_atencion)->format('H:i') : 'N/A'); ?></td>
            </tr>
            <tr>
                <td><span class="label">HORA FIN</span></td>
                <td class="value"><?php echo e($servicio->hora_fin_atencion ? \Carbon\Carbon::parse($servicio->hora_fin_atencion)->format('H:i') : 'N/A'); ?></td>
                <td><span class="label">DURACION SERVICIO</span></td>
                <td class="value" colspan="3">
                    <?php if($servicio->hora_inicio_atencion && $servicio->hora_fin_atencion): ?>
                        <?php echo e(\Carbon\Carbon::parse($servicio->hora_inicio_atencion)
                            ->diff(\Carbon\Carbon::parse($servicio->hora_fin_atencion))
                            ->format('%H:%I')); ?>

                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="section-title">DATOS DEL CLIENTE</div>
        <table class="table">
            <tr>
                <td style="width: 16%"><span class="label">IDENTIFICACION</span></td>
                <td style="width: 20%" class="value"><?php echo e($servicio->equipo->area->sede->cliente->documento_formateado ?? $servicio->equipo->area->sede->cliente->documento ?? 'N/A'); ?></td>
                <td style="width: 12%"><span class="label">NOMBRE</span></td>
                <td style="width: 22%" class="value"><?php echo e($servicio->equipo->area->sede->cliente->razon_social); ?></td>
                <td style="width: 12%"><span class="label">SEDE</span></td>
                <td style="width: 18%" class="value"><?php echo e($servicio->equipo->area->sede->nombre); ?></td>
            </tr>
            <tr>
                <td><span class="label">DIRECCION</span></td>
                <td class="value" colspan="5">
                    <?php
                        $sede = $servicio->equipo->area->sede ?? null;
                        $departamento = $sede?->municipio?->departamento?->nombre;
                        $municipio = $sede?->municipio?->nombre;
                        $barrio = $sede?->barrio?->nombre;
                        $direccion = $sede?->direccion;
                        $direccionPartes = array_filter([$departamento, $municipio, $barrio, $direccion]);
                    ?>
                    <?php echo e(!empty($direccionPartes) ? implode(', ', $direccionPartes) : 'N/A'); ?>

                </td>
            </tr>
            <tr>
                <td><span class="label">TELEFONO</span></td>
                <td class="value"><?php echo e($servicio->equipo->area->sede->telefono ?? 'N/A'); ?></td>
                <td><span class="label">CODIGO CONTRATO</span></td>
                <td class="value" colspan="3"><?php echo e($servicio->contrato ? ($servicio->contrato->numero_contrato ?? $servicio->contrato->codigo ?? 'N/A') : 'SIN CONTRATO'); ?></td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th style="width: 25%">INSTALACION</th>
                <th style="width: 25%">MANTENIMIENTO PREVENTIVO</th>
                <th style="width: 25%">SOPORTE</th>
                <th style="width: 25%">MANTENIMIENTO CORRECTIVO</th>
            </tr>
            <tr>
                <td class="value">
                    <span class="label">FACTURAR</span>
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'INSTALACION' ? 'checked' : ''); ?>"></span> SI
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'INSTALACION' ? '' : 'checked'); ?>"></span> NO
                </td>
                <td class="value">
                    <span class="label">FACTURAR</span>
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'MANTENIMIENTO_PREVENTIVO' ? 'checked' : ''); ?>"></span> SI
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'MANTENIMIENTO_PREVENTIVO' ? '' : 'checked'); ?>"></span> NO
                </td>
                <td class="value">
                    <span class="label">FACTURAR</span>
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'SOPORTE' ? 'checked' : ''); ?>"></span> SI
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'SOPORTE' ? '' : 'checked'); ?>"></span> NO
                </td>
                <td class="value">
                    <span class="label">FACTURAR</span>
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'MANTENIMIENTO_CORRECTIVO' ? 'checked' : ''); ?>"></span> SI
                    <span class="checkbox <?php echo e($servicio->tipo_servicio_informe === 'MANTENIMIENTO_CORRECTIVO' ? '' : 'checked'); ?>"></span> NO
                </td>
            </tr>
        </table>

        <div class="section-title">EQUIPOS ATENDIDOS</div>
        <table class="table">
            <tr>
                <th style="width: 14%">CODIGO EQUIPO</th>
                <th style="width: 24%">DESCRIPCION</th>
                <th style="width: 14%">MODELO</th>
                <th style="width: 16%">MARCA</th>
                <th style="width: 14%">SERIE</th>
                <th style="width: 18%">CONTRATO</th>
            </tr>
            <?php $__empty_1 = true; $__currentLoopData = ($equiposAtendidos ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipoAtendido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="value"><?php echo e($equipoAtendido->codigo_activo_cliente ?? ('EQ-' . $equipoAtendido->id)); ?></td>
                    <td class="value"><?php echo e($equipoAtendido->descripcion ?? 'N/A'); ?></td>
                    <td class="value"><?php echo e($equipoAtendido->modelo ?? 'N/A'); ?></td>
                    <td class="value"><?php echo e($equipoAtendido->marca->nombre ?? 'N/A'); ?></td>
                    <td class="value"><?php echo e($equipoAtendido->serial ?? 'N/A'); ?></td>
                    <td class="value"><?php echo e($equipoAtendido->contrato->numero_contrato ?? 'Sin contrato'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td class="value" colspan="6">No se registraron equipos atendidos.</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="section-title">DESCRIPCION DEL SERVICIO SOLICITADO</div>
        <div class="box"><?php echo e($servicio->descripcion_solicitud ?? $servicio->descripcion_problema ?? 'N/A'); ?></div>

        <div class="section-title">DIAGNOSTICO / VALIDACION DEL SERVICIO SOLICITADO</div>
        <div class="box"><?php echo e($servicio->diagnostico_validacion ?? $servicio->diagnostico ?? 'N/A'); ?></div>

        <div class="section-title">LABOR REALIZADA</div>
        <div class="box"><?php echo e($servicio->descripcion_atencion ?? 'N/A'); ?></div>

        <div class="section-title">OBSERVACIONES DEL INFORME</div>
        <div class="box small"><?php echo e($servicio->observaciones_informe ?? ''); ?></div>

        <table class="table">
            <tr>
                <td style="width: 25%"><span class="label">FUNCIONALIDAD APROBADA</span></td>
                <td style="width: 25%" class="value">
                    <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) >= 4 ? 'checked' : ''); ?>"></span> SI
                </td>
                <td style="width: 25%" class="value">
                    <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) >= 1 && ($servicio->calificacion_cliente ?? 0) < 4 ? 'checked' : ''); ?>"></span> NO
                </td>
                <td style="width: 25%" class="value">
                    <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) === 0 ? 'checked' : ''); ?>"></span> N/A
                </td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th colspan="6">COMPLEMENTOS / REPUESTOS</th>
                <th colspan="4">FACTURAR</th>
            </tr>
            <tr>
                <th style="width: 10%">CODIGO</th>
                <th style="width: 28%">DESCRIPCION</th>
                <th style="width: 12%">SERIE</th>
                <th style="width: 10%">CANTIDAD</th>
                <th style="width: 10%">CODIGO</th>
                <th style="width: 20%">DESCRIPCION</th>
                <th style="width: 5%">SI</th>
                <th style="width: 5%">NO</th>
                <th style="width: 5%">SERIE</th>
                <th style="width: 5%">CANTIDAD</th>
            </tr>
            <?php
                $repuestos = is_array($servicio->repuestos_utilizados ?? null) ? $servicio->repuestos_utilizados : [];
                $maxRows = 4;
            ?>
            <?php for($i = 0; $i < $maxRows; $i++): ?>
                <?php
                    $row = $repuestos[$i] ?? null;
                ?>
                <tr>
                    <td class="value"><?php echo e($row['codigo'] ?? ''); ?></td>
                    <td class="value"><?php echo e($row['descripcion'] ?? ''); ?></td>
                    <td class="value"><?php echo e($row['serie'] ?? ''); ?></td>
                    <td class="value"><?php echo e($row['cantidad'] ?? ''); ?></td>
                    <td class="value"></td>
                    <td class="value"></td>
                    <td class="value"></td>
                    <td class="value"></td>
                    <td class="value"></td>
                    <td class="value"></td>
                </tr>
            <?php endfor; ?>
        </table>

        <div class="section-title">ESPACIO PARA USO EXCLUSIVO DEL CLIENTE</div>
        <table class="table">
            <tr>
                <td style="width: 60%">
                    <span class="label">CONSIDERA UD QUE ESTE SERVICIO TECNICO FUE CERRADO SATISFACTORIAMENTE?</span>
                    <div style="margin-top: 6px;">
                        <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) >= 4 ? 'checked' : ''); ?>"></span> SI
                        <span style="margin-left: 16px;"></span>
                        <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) > 0 && ($servicio->calificacion_cliente ?? 0) < 4 ? 'checked' : ''); ?>"></span> NO
                        <span style="font-size: 8px; margin-left: 6px;">(Amplie la respuesta en comentarios y/o sugerencias)</span>
                    </div>
                </td>
                <td style="width: 40%">
                    <span class="label">CALIFIQUE EL SERVICIO PRESTADO</span>
                    <div style="margin-top: 6px;">
                        <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) >= 4 ? 'checked' : ''); ?>"></span> BUENO
                        <span style="margin-left: 10px;"></span>
                        <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) === 3 ? 'checked' : ''); ?>"></span> REGULAR
                        <span style="margin-left: 10px;"></span>
                        <span class="radio <?php echo e(($servicio->calificacion_cliente ?? 0) > 0 && ($servicio->calificacion_cliente ?? 0) <= 2 ? 'checked' : ''); ?>"></span> MALO
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">COMENTARIOS Y/O SUGERENCIAS:</span>
                    <div class="box small"><?php echo e($servicio->comentarios_cliente ?? ''); ?></div>
                </td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <td style="width: 40%">
                    <span class="label">RECIBE CLIENTE A SATISFACCION</span>
                    <div style="margin-top: 4px;">
                        <span class="label">NOMBRE</span> <span class="value"><?php echo e($servicio->persona_receptora_nombre); ?> <?php echo e($servicio->persona_receptora_apellido); ?></span><br>
                        <span class="label">CARGO</span> <span class="value">N/A</span><br>
                        <span class="label">FECHA</span> <span class="value"><?php echo e($servicio->fecha_atencion?->format('d/m/Y') ?? 'N/A'); ?></span>
                    </div>
                </td>
                <td style="width: 30%">
                    <span class="label">ENTREGA</span>
                    <div class="signature">
                        <?php if($firmaBase64): ?>
                            <img src="<?php echo e($firmaBase64); ?>" alt="Firma receptor">
                        <?php endif; ?>
                        <?php echo e($servicio->persona_receptora_documento ?? 'N/A'); ?>

                    </div>
                </td>
                <td style="width: 30%">
                    <span class="label">TECNICO RESPONSABLE</span>
                    <div class="signature">
                        <div><?php echo e($servicio->tecnicoResponsable->name ?? 'Tecnico'); ?></div>
                        <div class="muted"><?php echo e($servicio->tecnicoResponsable->email ?? ''); ?></div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            Derechos reservados a CEO GESTION 2026 Ver 1
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/incidencias/servicios/pdf/informe-tecnico-new.blade.php ENDPATH**/ ?>