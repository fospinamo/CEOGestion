# 📤 LISTA DE ARCHIVOS PARA FTP - PRODUCCIÓN
## Fecha: 2026-07-01

---

## 🎯 INSTRUCCIONES RÁPIDAS

1. **Descargar esta lista en tu cliente FTP**
2. **Navegar a:** `/htdocs/CEOGestion/`
3. **Copiar cada archivo** de Origen Local → Destino FTP
4. **Ejecutar migraciones** después de subir todos los archivos

---

## 📁 ESTRUCTURA DE CARPETAS DESTINO

```
CEOGestion/
├── app/
│   ├── Http/Controllers/
│   │   ├── Incidencias/
│   │   │   └── ServicioController.php (MODIFICADO)
│   │   └── Parametros/
│   │       ├── EmpresaController.php (MODIFICADO)
│   │       ├── EquipoController.php (MODIFICADO)
│   │       ├── EquipoDocumentoController.php (MODIFICADO)
│   │       ├── MantenimientoController.php (MODIFICADO)
│   │       ├── MantenimientoCalibrationController.php (MODIFICADO)
│   │       └── InformeFormatoController.php (NUEVO)
│   └── Models/
│       ├── Empresa.php (MODIFICADO)
│       ├── Equipo.php (MODIFICADO)
│       ├── Servicio.php (MODIFICADO)
│       └── InformeFormato.php (NUEVO)
├── database/
│   ├── migrations/
│   │   ├── 2026_06_16_000001_create_informe_formatos_table.php (NUEVO)
│   │   ├── 2026_06_16_000002_add_informe_formato_to_empresas_table.php (NUEVO)
│   │   ├── 2026_06_26_000001_remove_legacy_fields_from_servicios_table.php (NUEVO)
│   │   └── ... (muchos archivos MODIFICADOS)
│   ├── factories/
│   │   └── ServicioFactory.php (MODIFICADO)
│   └── seeders/
│       └── ServicioSeeder.php (MODIFICADO)
├── resources/
│   └── views/
│       ├── parametros/
│       │   ├── empresas/
│       │   │   ├── create.blade.php (MODIFICADO)
│       │   │   └── edit.blade.php (MODIFICADO)
│       │   ├── equipos/
│       │   │   ├── create.blade.php (MODIFICADO)
│       │   │   ├── index.blade.php (MODIFICADO)
│       │   │   ├── show.blade.php (MODIFICADO)
│       │   │   └── pdf.blade.php (MODIFICADO)
│       │   └── informe-formatos/ (NUEVA CARPETA)
│       │       ├── index.blade.php (NUEVO)
│       │       ├── create.blade.php (NUEVO)
│       │       └── show.blade.php (NUEVO)
│       ├── incidencias/servicios/
│       │   ├── index.blade.php (MODIFICADO)
│       │   ├── show.blade.php (MODIFICADO)
│       │   ├── attend.blade.php (MODIFICADO)
│       │   ├── estadisticas.blade.php (MODIFICADO)
│       │   └── pdf/
│       │       └── informe-tecnico-new.blade.php (MODIFICADO)
│       └── layouts/
│           └── app.blade.php (MODIFICADO)
└── routes/
    └── parametros.php (MODIFICADO)
```

---

## 📋 LISTA DETALLADA POR SECCIÓN

### SECCIÓN 1: CONTROLADORES (7 archivos)

#### Modificados (6)
```
# Incidencias
Local: app/Http/Controllers/Incidencias/ServicioController.php
FTP:   app/Http/Controllers/Incidencias/ServicioController.php
Tipo:  MODIFICADO (50+ líneas de cambios)

# Parametros - Empresa
Local: app/Http/Controllers/Parametros/EmpresaController.php
FTP:   app/Http/Controllers/Parametros/EmpresaController.php
Tipo:  MODIFICADO (15+ líneas de cambios)

# Parametros - Equipo
Local: app/Http/Controllers/Parametros/EquipoController.php
FTP:   app/Http/Controllers/Parametros/EquipoController.php
Tipo:  MODIFICADO (30+ líneas de cambios)

# Parametros - Equipo Documento
Local: app/Http/Controllers/Parametros/EquipoDocumentoController.php
FTP:   app/Http/Controllers/Parametros/EquipoDocumentoController.php
Tipo:  MODIFICADO (25+ líneas de cambios)

# Parametros - Mantenimiento
Local: app/Http/Controllers/Parametros/MantenimientoController.php
FTP:   app/Http/Controllers/Parametros/MantenimientoController.php
Tipo:  MODIFICADO (20+ líneas de cambios)

# Parametros - Mantenimiento Calibración
Local: app/Http/Controllers/Parametros/MantenimientoCalibrationController.php
FTP:   app/Http/Controllers/Parametros/MantenimientoCalibrationController.php
Tipo:  MODIFICADO (18+ líneas de cambios)
```

#### Nuevos (1)
```
# Parametros - Informe Formato (NUEVO)
Local: app/Http/Controllers/Parametros/InformeFormatoController.php
FTP:   app/Http/Controllers/Parametros/InformeFormatoController.php
Tipo:  NUEVO (84 líneas)
Métodos: index, create, store, show, edit, update, destroy
```

---

### SECCIÓN 2: MODELOS (4 archivos)

#### Modificados (3)
```
Local: app/Models/Empresa.php
FTP:   app/Models/Empresa.php
Tipo:  MODIFICADO (agregar relación con InformeFormato)

Local: app/Models/Equipo.php
FTP:   app/Models/Equipo.php
Tipo:  MODIFICADO (agregar relación con Marca)

Local: app/Models/Servicio.php
FTP:   app/Models/Servicio.php
Tipo:  MODIFICADO (cambiar tecnicoResponsable a tecnico)
```

#### Nuevos (1)
```
Local: app/Models/InformeFormato.php
FTP:   app/Models/InformeFormato.php
Tipo:  NUEVO (36 líneas)
Relaciones: hasMany empresas
```

---

### SECCIÓN 3: MIGRACIONES (3 nuevas + 6 modificadas)

#### Nuevas (3)
```
# Crear tabla informe_formatos
Local: database/migrations/2026_06_16_000001_create_informe_formatos_table.php
FTP:   database/migrations/2026_06_16_000001_create_informe_formatos_table.php
Tipo:  NUEVO
Tabla: informe_formatos (id, nombre, codigo, descripcion, blade_template, activo)

# Agregar informe_formato_id a empresas
Local: database/migrations/2026_06_16_000002_add_informe_formato_to_empresas_table.php
FTP:   database/migrations/2026_06_16_000002_add_informe_formato_to_empresas_table.php
Tipo:  NUEVO
Campo: empresas.informe_formato_id (foreign key)

# Remover campos legacy de servicios
Local: database/migrations/2026_06_26_000001_remove_legacy_fields_from_servicios_table.php
FTP:   database/migrations/2026_06_26_000001_remove_legacy_fields_from_servicios_table.php
Tipo:  NUEVO
Elimina: servicios.tecnico_asignado_id, servicios.tecnico_cedula
```

#### Modificadas (6)
```
Local: database/migrations/2026_05_28_000001_create_procesos_table.php
FTP:   database/migrations/2026_05_28_000001_create_procesos_table.php
Tipo:  MODIFICADO

Local: database/migrations/2026_05_28_000002_create_subprocesos_table.php
FTP:   database/migrations/2026_05_28_000002_create_subprocesos_table.php
Tipo:  MODIFICADO

Local: database/migrations/2026_06_02_000002_create_documentos_table.php
FTP:   database/migrations/2026_06_02_000002_create_documentos_table.php
Tipo:  MODIFICADO

Local: database/migrations/2026_06_02_000003_create_radicaciones_table.php
FTP:   database/migrations/2026_06_02_000003_create_radicaciones_table.php
Tipo:  MODIFICADO

Local: database/migrations/2026_06_02_000006_create_digitalizaciones_table.php
FTP:   database/migrations/2026_06_02_000006_create_digitalizaciones_table.php
Tipo:  MODIFICADO

Local: database/migrations/2026_06_09_072319_create_password_resets_table.php
FTP:   database/migrations/2026_06_09_072319_create_password_resets_table.php
Tipo:  MODIFICADO
```

---

### SECCIÓN 4: FACTORIES & SEEDERS (2 archivos)

```
Local: database/factories/ServicioFactory.php
FTP:   database/factories/ServicioFactory.php
Tipo:  MODIFICADO (actualizar referencias a tecnico_id)

Local: database/seeders/ServicioSeeder.php
FTP:   database/seeders/ServicioSeeder.php
Tipo:  MODIFICADO (actualizar referencias a tecnico_id)
```

---

### SECCIÓN 5: VISTAS (16 archivos)

#### Parametros - Empresas (2)
```
Local: resources/views/parametros/empresas/create.blade.php
FTP:   resources/views/parametros/empresas/create.blade.php
Tipo:  MODIFICADO (agregar select informe_formato_id)

Local: resources/views/parametros/empresas/edit.blade.php
FTP:   resources/views/parametros/empresas/edit.blade.php
Tipo:  MODIFICADO (agregar select informe_formato_id)
```

#### Parametros - Equipos (4)
```
Local: resources/views/parametros/equipos/create.blade.php
FTP:   resources/views/parametros/equipos/create.blade.php
Tipo:  MODIFICADO (agregar marca, cliente, sede)

Local: resources/views/parametros/equipos/index.blade.php
FTP:   resources/views/parametros/equipos/index.blade.php
Tipo:  MODIFICADO (agregar columna marca)

Local: resources/views/parametros/equipos/show.blade.php
FTP:   resources/views/parametros/equipos/show.blade.php
Tipo:  MODIFICADO (mostrar marca)

Local: resources/views/parametros/equipos/pdf.blade.php
FTP:   resources/views/parametros/equipos/pdf.blade.php
Tipo:  MODIFICADO (mostrar marca en PDF)
```

#### Parametros - Informe Formatos (3) **NUEVOS**
```
Local: resources/views/parametros/informe-formatos/index.blade.php
FTP:   resources/views/parametros/informe-formatos/index.blade.php
Tipo:  NUEVO (104 líneas - Listado DataTable)

Local: resources/views/parametros/informe-formatos/create.blade.php
FTP:   resources/views/parametros/informe-formatos/create.blade.php
Tipo:  NUEVO (76 líneas - Formulario crear/editar)

Local: resources/views/parametros/informe-formatos/show.blade.php
FTP:   resources/views/parametros/informe-formatos/show.blade.php
Tipo:  NUEVO (71 líneas - Detalle del formato)
```

#### Incidencias - Servicios (5)
```
Local: resources/views/incidencias/servicios/index.blade.php
FTP:   resources/views/incidencias/servicios/index.blade.php
Tipo:  MODIFICADO (cambiar tecnicoResponsable a tecnico)

Local: resources/views/incidencias/servicios/show.blade.php
FTP:   resources/views/incidencias/servicios/show.blade.php
Tipo:  MODIFICADO (cambiar tecnicoResponsable a tecnico)

Local: resources/views/incidencias/servicios/attend.blade.php
FTP:   resources/views/incidencias/servicios/attend.blade.php
Tipo:  MODIFICADO (mejoras en UI)

Local: resources/views/incidencias/servicios/estadisticas.blade.php
FTP:   resources/views/incidencias/servicios/estadisticas.blade.php
Tipo:  MODIFICADO (mejoras en gráficos)

Local: resources/views/incidencias/servicios/pdf/informe-tecnico-new.blade.php
FTP:   resources/views/incidencias/servicios/pdf/informe-tecnico-new.blade.php
Tipo:  MODIFICADO (cambiar tecnicoResponsable a tecnico)
```

#### Layouts (1)
```
Local: resources/views/layouts/app.blade.php
FTP:   resources/views/layouts/app.blade.php
Tipo:  MODIFICADO (agregar link "Informe Formatos" en menú)
```

---

### SECCIÓN 6: RUTAS (1 archivo)

```
Local: routes/parametros.php
FTP:   routes/parametros.php
Tipo:  MODIFICADO (agregar ruta del controlador InformeFormatoController)
Línea:  Route::resource('informe-formatos', InformeFormatoController::class);
```

---

### SECCIÓN 7: DOCUMENTACIÓN (1 archivo)

```
Local: PROTOCOLO_CAMBIOS_SEGURIDAD.md
FTP:   PROTOCOLO_CAMBIOS_SEGURIDAD.md
Tipo:  MODIFICADO (actualizar protocolo)
```

---

## 🔍 RESUMEN TÉCNICO

| Tipo | Cantidad | Acción |
|------|----------|--------|
| NUEVOS | 7 | Crear en FTP |
| MODIFICADOS | 45 | Reemplazar en FTP |
| CARPETAS NUEVAS | 1 | Crear carpeta `/parametros/informe-formatos/` |
| **TOTAL** | **52+1** | **52 archivos + 1 carpeta** |

---

## ⚠️ CAMBIOS CRÍTICOS

### 1. Cambio de Relación en Servicio
```
ANTES: $servicio->tecnicoResponsable->name
DESPUÉS: $servicio->tecnico->name

Afecta:
- 5 vistas
- ServicioController
- Modelo Servicio
```

### 2. Nueva Tabla `informe_formatos`
```
Tabla creada por: 2026_06_16_000001_create_informe_formatos_table.php
Requerida para: Gestionar formatos de informe por empresa
Migración: DEBE ejecutarse ANTES que la siguiente
```

### 3. Eliminación de Campos Legacy
```
Removidos por: 2026_06_26_000001_remove_legacy_fields_from_servicios_table.php
Campos: tecnico_asignado_id, tecnico_cedula
Motivo: Consolidación en campo tecnico_id
```

---

## 🚀 ORDEN DE COPIA RECOMENDADO

```
1. Copiar todas las migraciones (database/migrations/)
2. Copiar modelos (app/Models/)
3. Copiar controladores (app/Http/Controllers/)
4. Copiar rutas (routes/parametros.php)
5. Copiar vistas (resources/views/)
6. Copiar factories y seeders
7. Copiar documentación
```

---

## ✅ DESPUÉS DE SUBIR EN PRODUCCIÓN

```bash
# 1. Ejecutar migraciones
php artisan migrate --force

# 2. Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Verificar rutas
php artisan route:list | grep informe-formatos

# 4. Testing
# - Acceder a: /parametros/informe-formatos
# - Crear un nuevo formato
# - Asignar a una empresa
# - Editar un servicio (verificar técnico)
```

---

**Total de archivos a copiar:** 52  
**Tamaño estimado:** ~250 KB  
**Tiempo estimado:** 2-3 minutos vía FTP  
**Complejidad:** Media  
**Estado:** LISTO ✅

