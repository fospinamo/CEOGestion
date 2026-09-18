@extends('portal.cliente.layout')

@section('title', 'Servicios - Portal del Cliente')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-tools text-blue-500 mr-2"></i>
        Mis Servicios
    </h1>

    <!-- Botón para Crear Servicio -->
    <div class="mb-5">
        <x-button tag="button" onclick="document.getElementById('modalNuevoServicio').classList.remove('hidden');">
            <i class="fas fa-plus"></i> Reportar Nuevo Servicio
        </x-button>
    </div>

    @if($servicios->isEmpty())
        <x-card class="text-center py-10">
            <x-empty-state icon="fa-tools" message="No tienes servicios reportados." />
        </x-card>
    @else
        <x-card :padding="false">
            <table id="tablaServicios" class="w-full responsive">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Equipo</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Prioridad</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Fecha Reporte</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Técnico</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($servicios as $servicio)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3"><strong class="text-blue-500">#{{ $servicio->id }}</strong></td>
                            <td class="px-4 py-3">{{ $servicio->equipo?->codigo_activo_cliente ?? 'N/A' }}</td>
                            <td class="px-4 py-3"><x-badge variant="blue">{{ $servicio->tipo_servicio }}</x-badge></td>
                            <td class="px-4 py-3">
                                @php
                                    $prioridadMap = ['BAJA' => 'green', 'MEDIA' => 'yellow', 'ALTA' => 'red', 'CRITICA' => 'red'];
                                @endphp
                                <x-badge :variant="$prioridadMap[$servicio->prioridad] ?? 'gray'">{{ $servicio->prioridad }}</x-badge>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $estadoMap = ['REPORTADO' => 'blue', 'EN_ESPERA_ASIGNACION' => 'yellow', 'EN_PROCESO' => 'yellow', 'RESUELTO' => 'green', 'CERRADO' => 'gray'];
                                @endphp
                                <x-badge :variant="$estadoMap[$servicio->estado] ?? 'gray'">{{ str_replace('_', ' ', $servicio->estado) }}</x-badge>
                            </td>
                            <td class="px-4 py-3">{{ $servicio->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $servicio->tecnicoAsignado?->name ?? 'Sin asignar' }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-button href="{{ route('portal.servicios.detalle', $servicio->id) }}" class="!px-3 !py-1.5 !text-xs">
                                    <i class="fas fa-eye"></i> Ver
                                </x-button>
                                @if($servicio->estado === 'CERRADO' || $servicio->estado === 'RESUELTO')
                                    <x-button href="{{ route('portal.servicios.descargar', $servicio->id) }}" variant="success" class="!px-3 !py-1.5 !text-xs">
                                        <i class="fas fa-download"></i> Descargar
                                    </x-button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>
    @endif

    <!-- Modal Nuevo Servicio -->
    <div id="modalNuevoServicio" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50">
        <div class="bg-white mx-auto mt-[10%] p-7 rounded-lg w-[90%] max-w-[600px] max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">Reportar Nuevo Servicio</h2>
                <button onclick="this.closest('#modalNuevoServicio').classList.add('hidden');" class="text-gray-500 hover:text-gray-700 text-2xl border-0 bg-transparent cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('portal.servicios.crear') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Equipo *</label>
                    <select name="equipo_id" required class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                        <option value="">Selecciona un equipo</option>
                        @foreach($servicios->pluck('equipo')->unique('id') as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->codigo_activo_cliente }} - {{ $equipo->area?->nombre ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Tipo de Servicio *</label>
                    <select name="tipo_servicio" required class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                        <option value="">Selecciona el tipo</option>
                        <option value="PREVENTIVO">Preventivo</option>
                        <option value="CORRECTIVO">Correctivo</option>
                        <option value="INSTALACION">Instalación</option>
                        <option value="CONFIGURACION">Configuración</option>
                        <option value="CAPACITACION">Capacitación</option>
                        <option value="CONSULTA">Consulta</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Prioridad *</label>
                    <select name="prioridad" required class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                        <option value="">Selecciona la prioridad</option>
                        <option value="BAJA">Baja</option>
                        <option value="MEDIA">Media</option>
                        <option value="ALTA">Alta</option>
                        <option value="CRITICA">Crítica</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Descripción del Problema *</label>
                    <textarea name="descripcion_problema" required placeholder="Describe el problema detalladamente..." class="w-full p-2.5 border border-gray-300 rounded-md text-sm min-h-[100px] resize-y focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10"></textarea>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Reportado por *</label>
                    <input type="text" name="reportado_por" required placeholder="Tu nombre" class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Teléfono de Contacto *</label>
                    <input type="tel" name="telefono_contacto" required placeholder="+57 300 123 4567" class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-gray-700">Email de Contacto *</label>
                    <input type="email" name="email_contacto" required placeholder="tu@email.com" class="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10">
                </div>

                <div class="flex gap-3 justify-end">
                    <x-button tag="button" variant="danger" onclick="this.closest('#modalNuevoServicio').classList.add('hidden');">
                        Cancelar
                    </x-button>
                    <x-button tag="button" variant="primary">
                        <i class="fas fa-save"></i> Registrar Servicio
                    </x-button>
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
                    "columnDefs": [
                        { "orderable": false, "targets": 7 }
                    ],
                    "order": [[5, "desc"]],
                    "pageLength": 10,
                    "autoWidth": false,
                    "fixedHeader": false
                });
            });

            window.onclick = function(event) {
                const modal = document.getElementById('modalNuevoServicio');
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            }
        </script>
    @endsection
@endsection
