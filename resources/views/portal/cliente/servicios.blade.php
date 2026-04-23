@extends('portal.cliente.layout')

@section('title', 'Servicios - Portal del Cliente')

@section('content')
    <h1 style="margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-tools" style="margin-right: 10px; color: #3b82f6;"></i>
        Mis Servicios
    </h1>

    <!-- Botón para Crear Servicio -->
    <div style="margin-bottom: 20px;">
        <button class="btn btn-primary" onclick="document.getElementById('modalNuevoServicio').style.display='block';">
            <i class="fas fa-plus"></i> Reportar Nuevo Servicio
        </button>
    </div>

    @if($servicios->isEmpty())
        <div class="card" style="text-align: center; padding: 40px;">
            <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 16px;">No tienes servicios reportados.</p>
        </div>
    @else
        <div class="card">
            <table id="tablaServicios" class="responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Equipo</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha Reporte</th>
                        <th>Técnico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                        <tr>
                            <td>
                                <strong style="color: #3b82f6;">#{{ $servicio->id }}</strong>
                            </td>
                            <td>
                                {{ $servicio->equipo?->codigo_interno ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge" style="background: #cffafe; color: #164e63;">
                                    {{ $servicio->tipo_servicio }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $prioridadConfig = [
                                        'BAJA' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                        'MEDIA' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'ALTA' => ['bg' => '#fed7aa', 'text' => '#92400b'],
                                        'CRITICA' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $config = $prioridadConfig[$servicio->prioridad] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                <span class="badge" style="background: {{ $config['bg'] }}; color: {{ $config['text'] }};">
                                    {{ $servicio->prioridad }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $estadoConfig = [
                                        'REPORTADO' => ['bg' => '#cffafe', 'text' => '#164e63'],
                                        'EN_ESPERA_ASIGNACION' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'EN_PROCESO' => ['bg' => '#fed7aa', 'text' => '#92400b'],
                                        'RESUELTO' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'CERRADO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    ];
                                    $config = $estadoConfig[$servicio->estado] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                <span class="badge" style="background: {{ $config['bg'] }}; color: {{ $config['text'] }};">
                                    {{ str_replace('_', ' ', $servicio->estado) }}
                                </span>
                            </td>
                            <td>
                                {{ $servicio->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                {{ $servicio->tecnicoAsignado?->name ?? 'Sin asignar' }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('portal.servicios.detalle', $servicio->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                @if($servicio->estado === 'CERRADO' || $servicio->estado === 'RESUELTO')
                                    <a href="{{ route('portal.servicios.descargar', $servicio->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; background: #10b981;">
                                        <i class="fas fa-download"></i> Descargar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modal Nuevo Servicio -->
    <div id="modalNuevoServicio" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: white; margin: 10% auto; padding: 30px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 700;">Reportar Nuevo Servicio</h2>
                <button onclick="document.getElementById('modalNuevoServicio').style.display='none';" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">×</button>
            </div>

            <form action="{{ route('portal.servicios.crear') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Equipo *</label>
                    <select name="equipo_id" required>
                        <option value="">Selecciona un equipo</option>
                        @foreach($servicios->pluck('equipo')->unique('id') as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->codigo_interno }} - {{ $equipo->area?->nombre ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de Servicio *</label>
                    <select name="tipo_servicio" required>
                        <option value="">Selecciona el tipo</option>
                        <option value="PREVENTIVO">Preventivo</option>
                        <option value="CORRECTIVO">Correctivo</option>
                        <option value="INSTALACION">Instalación</option>
                        <option value="CONFIGURACION">Configuración</option>
                        <option value="CAPACITACION">Capacitación</option>
                        <option value="CONSULTA">Consulta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Prioridad *</label>
                    <select name="prioridad" required>
                        <option value="">Selecciona la prioridad</option>
                        <option value="BAJA">Baja</option>
                        <option value="MEDIA">Media</option>
                        <option value="ALTA">Alta</option>
                        <option value="CRITICA">Crítica</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Descripción del Problema *</label>
                    <textarea name="descripcion_problema" required placeholder="Describe el problema detalladamente..."></textarea>
                </div>

                <div class="form-group">
                    <label>Reportado por *</label>
                    <input type="text" name="reportado_por" required placeholder="Tu nombre">
                </div>

                <div class="form-group">
                    <label>Teléfono de Contacto *</label>
                    <input type="tel" name="telefono_contacto" required placeholder="+57 300 123 4567">
                </div>

                <div class="form-group">
                    <label>Email de Contacto *</label>
                    <input type="email" name="email_contacto" required placeholder="tu@email.com">
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('modalNuevoServicio').style.display='none';">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Servicio
                    </button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#tablaServicios').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                    },
                    "responsive": true,
                    "columnDefs": [
                        { "orderable": false, "targets": 7 }
                    ],
                    "order": [[5, "desc"]],
                    "pageLength": 10
                });
            });

            // Cerrar modal al hacer click fuera
            window.onclick = function(event) {
                const modal = document.getElementById('modalNuevoServicio');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }
        </script>
    @endsection
@endsection
