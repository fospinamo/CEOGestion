# Proposal: Unificar Sistema de Mantenimiento Preventivo y Generación Automática de Servicios

## Intent

Resolver la deuda técnica de dos sistemas de mantenimiento desconectados (MantenimientoProgramado y MantenimientoCalibración) que no generan servicios automáticamente. El objetivo es habilitar la generación automática de servicios preventivos para equipos con mantenimiento programado.

## Scope

### In Scope
- Consolidar MantenimientoProgramado como sistema unificado de mantenimiento preventivo
- Implementar comando Artisan para generación automática de servicios preventivos
- Registrar rutas faltantes para MantenimientoCalibrationController
- Corregir relación faltante en modelo Equipo
- Eliminar duplicación de campos en tabla equipos
- Crear vistas faltantes para MantenimientoProgramado

### Out of Scope
- Modificar lógica de negocio existente de mantenimiento
- Cambiar tipos de servicio existentes
- Implementar sistema de calibración (solo mantenimiento preventivo)
- Modificar interfaz de usuario existente

## Capabilities

### New Capabilities
- `mantenimiento-preventivo-automatico`: Generación automática de servicios preventivos basado en programación de mantenimiento
- `consolidacion-mantenimiento`: Unificación de sistemas de mantenimiento existentes

### Modified Capabilities
- `gestion-equipos`: Adición de relación con mantenimientos calibración y limpieza de campos duplicados

## Approach

**Opción Recomendada: Completar Sistema A (MantenimientoProgramado)**

Razón: Ya tiene lógica de generación de servicios (`asignarATecnico()`), controlador completo con CRUD y reportes, y 14 rutas definidas. Es la base más sólida para construir el sistema unificado.

Pasos:
1. Registrar rutas faltantes para MantenimientoCalibrationController
2. Agregar relación faltante en Equipo model
3. Crear comando Artisan para generación automática de servicios
4. Programar tarea en kernel para ejecución diaria
5. Crear vistas faltantes para MantenimientoProgramado
6. Limpiar campos duplicados en tabla equipos

## Affected Areas

| Area | Impact | Description |
|------|--------|-------------|
| `app/Console/Commands/` | New | Comando Artisan para generación de servicios |
| `app/Console/Kernel.php` | Modified | Programación de tarea automática |
| `app/Models/Equipo.php` | Modified | Agregar relación faltante, limpiar campos |
| `app/Models/MantenimientoProgramado.php` | Modified | Mejorar lógica de generación |
| `routes/parametros.php` | Modified | Registrar rutas faltantes |
| `resources/views/parametros/mantenimiento/` | New | Crear vistas faltantes |

## Risks

| Risk | Likelihood | Mitigation |
|------|------------|------------|
| Datos existentes inconsistentes por campos duplicados | High | Migración de datos antes de eliminar columnas |
| Rutas existentes pueden conflicto | Medium | Verificar naming conventions antes de registrar |
| Comando Artisan puede fallar en producción | Low | Tests unitarios y ejecución en staging primero |

## Rollback Plan

1. Revertir cambios en Equipo model (restaurar campos eliminados)
2. Eliminar comando Artisan y programación en kernel
3. Restaurar rutas originales en parametros.php
4. Ejecutar migrate:rollback si se crearon migraciones

## Dependencies

- Ninguna dependencia externa
- Requiere que existan equipos con mantenimiento programado para testing

## Success Criteria

- [ ] Comando Artisan genera servicios preventivos automáticamente
- [ ] Tarea programada se ejecuta diariamente sin errores
- [ ] MantenimientoCalibrationController funciona con rutas registradas
- [ ] No hay campos duplicados en tabla equipos
- [ ] Todas las vistas de mantenimiento funcionan correctamente
