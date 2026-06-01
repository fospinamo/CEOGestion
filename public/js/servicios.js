// servicios.js - Funcionalidades para el registro dinámico de servicios
// Versión mejorada con mejor manejo de errores

console.log('📄 servicios.js iniciando carga...');

$(document).ready(function() {
    console.log('✅ servicios.js cargado y DOM listo');
    console.log('jQuery versión:', jQuery.fn.jquery);
    
    // Verificar que los elementos existan
    const elementos = {
        cliente_id: $('#cliente_id'),
        sede_id: $('#sede_id'),
        area_id: $('#area_id'),
        equipo_id: $('#equipo_id'),
        tipo_servicio: $('#tipo_servicio'),
        prioridad: $('#prioridad'),
        form_servicio: $('#form-servicio'),
        contrato_info: $('#contrato_info')
    };

    const editState = {
        isEdit: String(elementos.form_servicio.data('is-edit') || '') === '1',
        selectedEquipoId: String(elementos.form_servicio.data('selected-equipo-id') || ''),
        selectedTipoServicio: String(elementos.form_servicio.data('selected-tipo-servicio') || ''),
        applyingInitialState: false
    };
    
    console.log('🔍 Elementos encontrados:', {
        cliente_id: elementos.cliente_id.length,
        sede_id: elementos.sede_id.length,
        area_id: elementos.area_id.length,
        equipo_id: elementos.equipo_id.length,
        tipo_servicio: elementos.tipo_servicio.length,
        prioridad: elementos.prioridad.length,
        form_servicio: elementos.form_servicio.length,
        contrato_info: elementos.contrato_info.length
    });
    
    if (elementos.cliente_id.length === 0) {
        console.error('❌ No se encontró #cliente_id - formulario incompleto');
        return;
    }
    
    // Almacenar opciones originales (verificando que existan)
    const sedeOptions = elementos.sede_id.length > 0 ? elementos.sede_id.html() : '';
    const areaOptions = elementos.area_id.length > 0 ? elementos.area_id.html() : '';
    console.log('📦 Opciones almacenadas:', { sedesCount: elementos.sede_id.find('option').length, areasCount: elementos.area_id.find('option').length });
    
    // ========== FILTRO 1: Cliente → Sedes ==========
    elementos.cliente_id.on('change', function() {
        const clienteId = $(this).val();
        console.log('🔍 FILTRO 1: Cliente seleccionado -', clienteId);
        
        if (clienteId) {
            // Filtrar sedes
            elementos.sede_id
                .prop('disabled', false)
                .html(sedeOptions)
                .find('option').each(function() {
                    if ($(this).val() !== '' && $(this).data('cliente-id') != clienteId) {
                        $(this).hide();
                    }
                });
            
            // Resetear área y equipo SIN disparar eventos change
            elementos.area_id
                .off('change')
                .html(areaOptions)
                .val('')
                .prop('disabled', true)
                .on('change', handleAreaChange);
            
            elementos.equipo_id
                .empty()
                .append('<option value="">Seleccione área primero</option>')
                .prop('disabled', true);
            
            elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
            elementos.contrato_info.html('');
        } else {
            // Reset
            elementos.sede_id
                .html(sedeOptions)
                .val('')
                .prop('disabled', true);
            
            elementos.area_id
                .off('change')
                .html(areaOptions)
                .val('')
                .prop('disabled', true)
                .on('change', handleAreaChange);
            
            elementos.equipo_id
                .empty()
                .append('<option value="">Seleccione cliente primero</option>')
                .prop('disabled', true);
            
            elementos.tipo_servicio.empty().append('<option value="">Seleccione cliente primero</option>').prop('disabled', true);
            elementos.contrato_info.html('');
        }
    });
    
    // ========== FILTRO 2: Sede → Áreas ==========
    elementos.sede_id.on('change', function() {
        const sedeId = $(this).val();
        console.log('🔍 FILTRO 2: Sede seleccionada -', sedeId);
        
        if (sedeId) {
            // Filtrar áreas
            elementos.area_id
                .prop('disabled', false)
                .html(areaOptions)
                .find('option').each(function() {
                    if ($(this).val() !== '' && $(this).data('sede-id') != sedeId) {
                        $(this).hide();
                    }
                });
            
            // Resetear equipo SIN disparar evento change
            elementos.equipo_id
                .empty()
                .append('<option value="">Seleccione área primero</option>')
                .prop('disabled', true);
            
            elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
            elementos.contrato_info.html('');
        } else {
            // Reset
            elementos.area_id
                .off('change')
                .html(areaOptions)
                .val('')
                .prop('disabled', true)
                .on('change', handleAreaChange);
            
            elementos.equipo_id
                .empty()
                .append('<option value="">Seleccione área primero</option>')
                .prop('disabled', true);
            
            elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
            elementos.contrato_info.html('');
        }
    });
    
    // Función handler para cambio de área (reutilizable)
    function handleAreaChange() {
        const areaId = elementos.area_id.val();
        const selectedEquipoForLoad = editState.applyingInitialState ? editState.selectedEquipoId : '';
        console.log('🔍 FILTRO 3: Area seleccionada -', areaId);
        
        if (areaId) {
            console.log('📡 Enviando AJAX a: /incidencias/incidencias/servicios/equipos-area/' + areaId);
            // Cargar equipos del área
            $.ajax({
                url: `/incidencias/servicios/equipos-area/${areaId}`,
                type: 'GET',
                dataType: 'json',
                success: function(equipos) {
                    console.log('✅ AJAX exitoso, equipos recibidos:', equipos);
                    const $equipoSelect = elementos.equipo_id;
                    $equipoSelect.empty().append('<option value="">Seleccione un equipo</option>').prop('disabled', false);
                    
                    if (equipos.length === 0) {
                        console.warn('⚠️ No hay equipos en esta área');
                        $equipoSelect.append('<option value="">No hay equipos en esta área</option>').prop('disabled', true);
                    } else {
                        equipos.forEach(equipo => {
                            const isSelected = selectedEquipoForLoad && String(equipo.id) === String(selectedEquipoForLoad);
                            $equipoSelect.append(`
                                <option value="${equipo.id}" ${isSelected ? 'selected' : ''}>
                                    ${equipo.codigo_interno} - ${equipo.marca} ${equipo.modelo} (${equipo.estado_operativo})
                                </option>
                            `);
                        });
                    }

                    if (selectedEquipoForLoad) {
                        $equipoSelect.val(String(selectedEquipoForLoad));
                        $equipoSelect.trigger('change');
                    }
                    
                    elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
                    elementos.contrato_info.html('');
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error en AJAX:', status, error);
                    console.error('Respuesta:', xhr.responseText);
                    elementos.equipo_id
                        .empty()
                        .append('<option value="">Error al cargar equipos</option>')
                        .prop('disabled', true);
                }
            });
        } else {
            // Reset
            elementos.equipo_id
                .empty()
                .append('<option value="">Seleccione área primero</option>')
                .prop('disabled', true);
            
            elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
            elementos.contrato_info.html('');
        }
    }
    
    // ========== FILTRO 3: Área → Equipos ==========
    elementos.area_id.on('change', handleAreaChange);
    
    // ========== FILTRO 4: Equipo → Contrato y Tipos de Servicio ==========
    elementos.equipo_id.on('change', function() {
        const equipoId = $(this).val();
        const clienteId = elementos.cliente_id.val();
        console.log('🔍 FILTRO 4: Equipo seleccionado -', equipoId, '| Cliente:', clienteId);
        console.log('Estado de condición (equipoId && clienteId):', !!(equipoId && clienteId));
        
        if (equipoId && clienteId) {
            // Mostrar cargando
            $('#contrato-status').hide();
            elementos.tipo_servicio.empty().append('<option value="">Cargando servicios...</option>').prop('disabled', true);
            
            console.log('📡 FILTRO 4: Enviando AJAX a: /incidencias/servicios/contrato-activo/' + clienteId);
            // Cargar contrato activo y servicios cubiertos
            $.ajax({
                url: `/incidencias/servicios/contrato-activo/${clienteId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('✅ AJAX FILTRO 4 exitoso, datos:', data);
                    
                    // ============ CONTRATO ACTIVO ============
                    $('#contrato-status').show();
                    $('#contrato-icon').text('✅');
                    $('#contrato-titulo').text('Contrato Vigente');
                    $('#contrato-mensaje').html(`
                        <strong>${data.contrato.numero_contrato}</strong><br>
                        📅 Válido desde ${formatDate(data.contrato.fecha_inicio)} hasta ${formatDate(data.contrato.fecha_fin)}
                    `);
                    $('#contrato-detalles').html(`
                        ⏱️ <strong>SLA:</strong> Respuesta en ${data.sla_respuesta}h | Solución en ${data.sla_solucion}h<br>
                        📋 <strong>Servicios cubiertos:</strong> ${data.servicios_cubiertos.join(', ')}
                    `);
                    $('#contrato-status').removeClass('border-yellow-200 bg-yellow-50').addClass('border-green-200 bg-green-50');
                    
                    // Cargar tipos de servicio permitidos
                    const $tipoServicio = elementos.tipo_servicio;
                    $tipoServicio.empty().append('<option value="">Seleccione tipo de servicio</option>').prop('disabled', false);
                    
                    const tiposPermitidos = data.servicios_cubiertos;
                    const todosTipos = ['CORRECTIVO', 'PREVENTIVO', 'INSTALACION', 'CONFIGURACION', 'CAPACITACION', 'CONSULTA'];
                    
                    console.log('📋 Servicios cubiertos:', tiposPermitidos, '| Cantidad:', tiposPermitidos.length);
                    
                    if (tiposPermitidos.length === 0) {
                        // Si no hay servicios específicos, permitir todos
                        console.log('⚠️ Sin servicios específicos, permitiendo todos los tipos');
                        todosTipos.forEach(tipo => {
                            $tipoServicio.append(`<option value="${tipo}">${tipo}</option>`);
                        });
                    } else {
                        // Si hay servicios específicos, mostrar solo los permitidos
                        todosTipos.forEach(tipo => {
                            if (tiposPermitidos.includes(tipo)) {
                                $tipoServicio.append(`<option value="${tipo}">${tipo} ✅ (Cubierto)</option>`);
                            } else {
                                $tipoServicio.append(`<option value="${tipo}" disabled>${tipo} ❌ (No incluido)</option>`);
                            }
                        });
                    }

                    if (editState.applyingInitialState && editState.selectedTipoServicio) {
                        $tipoServicio.val(editState.selectedTipoServicio);
                    }
                    
                    // Guardar datos del contrato en campos ocultos
                    $('#contrato_id').val(data.contrato.id);
                    $('#sla_respuesta').val(data.sla_respuesta);
                    $('#sla_solucion').val(data.sla_solucion);
                    
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error FILTRO 4 AJAX:', status, error);
                    console.error('Respuesta:', xhr.responseText);
                    
                    // ============ SIN CONTRATO - SERVICIOS FUERA DE CONTRATO ============
                    $('#contrato-status').show();
                    $('#contrato-icon').text('⚠️');
                    $('#contrato-titulo').text('Servicio Fuera de Contrato');
                    $('#contrato-mensaje').html(`
                        <strong>El cliente NO tiene contrato activo vigente</strong><br>
                        Este servicio se registrará como <strong style="color: #dc2626;">FUERA DE CONTRATO</strong>
                    `);
                    $('#contrato-detalles').html(`
                        📌 Se aplicarán SLAs por defecto (4h respuesta, 24h solución)<br>
                        💰 Este servicio será facturado por separado
                    `);
                    $('#contrato-status').removeClass('border-green-200 bg-green-50').addClass('border-yellow-200 bg-yellow-50');
                    
                    // Permitir todos los tipos de servicio sin contrato
                    const $tipoServicio = elementos.tipo_servicio;
                    $tipoServicio.empty().append('<option value="">Seleccione tipo de servicio</option>').prop('disabled', false);
                    
                    const todosTipos = ['CORRECTIVO', 'PREVENTIVO', 'INSTALACION', 'CONFIGURACION', 'CAPACITACION', 'CONSULTA'];
                    todosTipos.forEach(tipo => {
                        $tipoServicio.append(`<option value="${tipo}">${tipo}</option>`);
                    });

                    if (editState.applyingInitialState && editState.selectedTipoServicio) {
                        $tipoServicio.val(editState.selectedTipoServicio);
                    }
                    
                    // Limpiar contrato ID para indicar que es fuera de contrato
                    $('#contrato_id').val('');
                    $('#sla_respuesta').val('4');
                    $('#sla_solucion').val('24');
                }
            });
        } else {
            // Reset
            $('#contrato-status').hide();
            elementos.tipo_servicio.empty().append('<option value="">Seleccione equipo primero</option>').prop('disabled', true);
        }
    });
    
    // Validación antes de enviar el formulario
    if (elementos.form_servicio.length > 0) {
        elementos.form_servicio.on('submit', function(e) {
            // Validar que todos los selects requeridos tengan valores
            if (!elementos.cliente_id.val() || !elementos.sede_id.val() || !elementos.area_id.val() || !elementos.equipo_id.val()) {
                alert('⚠️ Por favor, complete toda la información de ubicación (Cliente, Sede, Área y Equipo)');
                e.preventDefault();
                return false;
            }
            
            if (!elementos.tipo_servicio.val()) {
                alert('⚠️ Debe seleccionar un tipo de servicio');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    }
    
    // ========== MODAL CREAR EQUIPO ==========
    const btnCrearEquipo = $('#btn-crear-equipo');
    const modalCrearEquipo = $('#modal-crear-equipo');
    const formCrearEquipo = $('#form-crear-equipo');
    
    // Mostrar/ocultar botón crear equipo según el área
    elementos.area_id.on('change', function() {
        if ($(this).val()) {
            btnCrearEquipo.show();
        } else {
            btnCrearEquipo.hide();
        }
    });
    
    // Abrir modal
    btnCrearEquipo.on('click', function() {
        const areaId = elementos.area_id.val();
        $('#modal-area_id').val(areaId);
        $('#modal-codigo_interno').val('');
        $('#modal-marca').val('');
        $('#modal-modelo').val('');
        $('#modal-serial').val('');
        $('#modal-descripcion').val('');
        modalCrearEquipo.removeClass('hidden');
    });
    
    // Cerrar modal al hacer clic fuera
    modalCrearEquipo.on('click', function(e) {
        if (e.target === this) {
            modalCrearEquipo.addClass('hidden');
        }
    });
    
    // Crear equipo via AJAX
    formCrearEquipo.on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = $('#btn-submit-equipo');
        const btnText = btn.html();
        
        btn.prop('disabled', true).html('⏳ Creando...');
        
        $.ajax({
            url: '/incidencias/servicios/crear-equipo',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('✅ Equipo creado:', response.equipo);
                
                // Cerrar modal
                modalCrearEquipo.addClass('hidden');
                
                // Recargar equipos del área
                const areaId = elementos.area_id.val();
                handleAreaChange();
                
                // Seleccionar el equipo recién creado
                setTimeout(function() {
                    elementos.equipo_id.val(response.equipo.id).trigger('change');
                    
                    // Mostrar mensaje de éxito
                    alert('✅ Equipo creado exitosamente: ' + response.equipo.codigo_interno);
                }, 500);
            },
            error: function(xhr, status, error) {
                console.error('❌ Error al crear equipo:', error);
                const errorData = xhr.responseJSON;
                alert('❌ Error: ' + (errorData?.message || 'No se pudo crear el equipo'));
                btn.prop('disabled', false).html(btnText);
            }
        });
    });

    // Rehidratar cascada en edición para mostrar valores existentes
    function hydrateEditForm() {
        if (!editState.isEdit) {
            return;
        }

        const clienteId = elementos.cliente_id.val();
        const sedeId = elementos.sede_id.val();
        const areaId = elementos.area_id.val();

        if (!clienteId || !sedeId || !areaId) {
            return;
        }

        editState.applyingInitialState = true;

        elementos.cliente_id.val(clienteId).trigger('change');
        elementos.sede_id.val(sedeId).trigger('change');
        elementos.area_id.val(areaId).trigger('change');

        // Después de inicializar, desactivar modo de hidratación.
        setTimeout(function() {
            editState.applyingInitialState = false;
        }, 1200);
    }

    hydrateEditForm();
    
    console.log('✅ Todos los event listeners configurados correctamente');
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
