<?php $__env->startSection('title', 'Editar Equipo'); ?>
<?php $__env->startSection('page-title', 'Editar Equipo'); ?>
<?php $__env->startSection('page-description', 'Actualizar información del equipo TI'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('parametros.equipos.form', [
        'equipo' => $equipo ?? null,
        'marcas' => $marcas ?? [],
        'clientes' => $clientes ?? [],
        'sedes' => $sedes ?? [],
        'areas' => $areas ?? [],
        'empresas' => $empresas ?? [],
        'tipos' => $tipos ?? [],
        'contratos' => $contratos ?? []
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/parametros/equipos/edit.blade.php ENDPATH**/ ?>