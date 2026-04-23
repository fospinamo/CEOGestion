// servicios.js - Funcionalidades para el registro dinámico de servicios

$(document).ready(function() {
    // Cuando cambia el cliente
    $('#cliente_id').on('change', function() {
        const clienteId = $(this).val();
        
        if (clienteId) {
            // Limpiar y deshabilitar selects mientras carga
            $('#equipo_id').empty().append('<option value="">Cargando equipos...</option>').prop('disabled', true);
            $('#tipo_servicio').empty().append('<option value="">Cargando servicios...</option>').prop('disabled', true);
            $('#contrato_info').html('<div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded"><div class="text-yellow-800">⏳ Verificando contrato y equipos...</div></div>');
            
            // Cargar equipos del cliente
            $.ajax({
                url: `/servicios/equipos/${clienteId}`,
                type: 'GET',
                success: function(equipos) {
                    const $equipoSelect = $('#equipo_id');
                    $equipoSelect.empty().append('<option value="">Seleccione un equipo</option>').prop('disabled', false);
                    
                    if (equipos.length === 0) {
                        $equipoSelect.append('<option value="">No hay equipos registrados para este cliente</option>');
                    } else {
                        equipos.forEach(equipo => {
                            $equipoSelect.append(`
                                <option value="${equipo.id}">
                                    ${equipo.codigo_interno} - ${equipo.marca} ${equipo.modelo} (SN: ${equipo.serial})
                                </option>
                            `);
                        });
                    }
                },
                error: function() {
                    $('#equipo_id').empty()
                        .append('<option value="">Error al cargar equipos</option>')
                        .prop('disabled', true);
                }
            });
            
            // Cargar contrato activo y servicios cubiertos
            $.ajax({
                url: `/servicios/contrato-activo/${clienteId}`,
                type: 'GET',
                success: function(data) {
                    // Mostrar información del contrato
                    $('#contrato_info').html(`
                        <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded">
                            <div class="font-bold text-green-800">✅ Contrato Activo: ${data.contrato.numero_contrato}</div>
                            <div class="text-sm text-green-700 mt-2 grid grid-cols-2 gap-2">
                                <div>📅 <strong>Desde:</strong> ${formatDate(data.contrato.fecha_inicio)}</div>
                                <div>📅 <strong>Hasta:</strong> ${formatDate(data.contrato.fecha_fin)}</div>
                                <div>⏱️ <strong>SLA Respuesta:</strong> ${data.sla_respuesta} horas</div>
                                <div>⏱️ <strong>SLA Solución:</strong> ${data.sla_solucion} horas</div>
                                <div class="col-span-2">📋 <strong>Servicios Cubiertos:</strong> ${data.servicios_cubiertos.join(', ')}</div>
                            </div>
                        </div>
                    `);
                    
                    // Cargar tipos de servicio permitidos
                    const $tipoServicio = $('#tipo_servicio');
                    $tipoServicio.empty().append('<option value="">Seleccione tipo de servicio</option>').prop('disabled', false);
                    
                    const tiposPermitidos = data.servicios_cubiertos;
                    const todosTipos = ['CORRECTIVO', 'PREVENTIVO', 'INSTALACION', 'CONFIGURACION', 'CAPACITACION', 'CONSULTA'];
                    
                    todosTipos.forEach(tipo => {
                        if (tiposPermitidos.includes(tipo)) {
                            $tipoServicio.append(`<option value="${tipo}">${tipo} ✅ (Cubierto)</option>`);
                        } else {
                            $tipoServicio.append(`<option value="${tipo}" disabled>${tipo} ❌ (No incluido)</option>`);
                        }
                    });
                    
                    // Guardar datos del contrato en campos ocultos
                    $('#contrato_id').val(data.contrato.id);
                    $('#sla_respuesta').val(data.sla_respuesta);
                    $('#sla_solucion').val(data.sla_solucion);
                    
                },
                error: function() {
                    $('#contrato_info').html(`
                        <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded">
                            <div class="font-bold text-red-800">⚠️ Cliente sin contrato activo</div>
                            <div class="text-sm text-red-700 mt-1">No se pueden registrar servicios hasta que el cliente tenga un contrato vigente.</div>
                        </div>
                    `);
                    $('#tipo_servicio').empty().append('<option value="">No disponible - Sin contrato activo</option>').prop('disabled', true);
                    $('#equipo_id').prop('disabled', true);
                }
            });
        } else {
            // Resetear formulario si no hay cliente seleccionado
            $('#equipo_id').empty().append('<option value="">Primero seleccione un cliente</option>').prop('disabled', true);
            $('#tipo_servicio').empty().append('<option value="">Primero seleccione un cliente</option>').prop('disabled', true);
            $('#contrato_info').empty();
        }
    });
    
    // Validación antes de enviar el formulario
    $('#form-servicio').on('submit', function(e) {
        const clienteId = $('#cliente_id').val();
        const equipoId = $('#equipo_id').val();
        const tipoServicio = $('#tipo_servicio').val();
        
        if (!clienteId) {
            alert('⚠️ Debe seleccionar un cliente');
            e.preventDefault();
            return false;
        }
        
        if (!equipoId) {
            alert('⚠️ Debe seleccionar un equipo');
            e.preventDefault();
            return false;
        }
        
        if (!tipoServicio) {
            alert('⚠️ Debe seleccionar un tipo de servicio');
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});

// Función auxiliar para formatear fechas
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}
