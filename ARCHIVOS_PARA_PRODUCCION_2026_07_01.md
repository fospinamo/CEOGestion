# 📦 ARCHIVOS PARA SUBIR A PRODUCCIÓN
## Fecha: 2026-07-01 | Última carga a producción: 2026-05-27

---

## 📋 RESUMEN

**Período:** 27 de mayo 2026 → 1 de julio 2026 (35 días de cambios)

**Total de cambios:**
- ✅ 1 Módulo NUEVO (Informe Formatos)
- 📝 36 archivos modificados
- 🆕 3 migraciones nuevas
- 🔧 13 controladores actualizados
- 📊 3 modelos actualizados
- 🎨 16 vistas actualizadas

---

## 🚀 ORDEN DE EJECUCIÓN EN PRODUCCIÓN

### PASO 1: Subir archivos (1h)
```
Origen Local               →  Destino FTP
─────────────────────────     ──────────────────
app/                           app/
database/migrations/           database/migrations/
resources/views/               resources/views/
routes/                        routes/
```

### PASO 2: Ejecutar migraciones (10 min)
```bash
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### PASO 3: Testing (20 min)
```
Validar en navegador:
- Login: admin@ceogestion.com
- Parametros → Equipos
- Parametros → Servicios
- Parametros → Informe Formatos (NUEVO)
```

---

## 🆕 MÓDULO NUEVO: Informe Formatos

### Descripción
Gestión de plantillas de informe técnico por empresa. Permite que cada empresa tenga su propio formato de informe.

### Archivos Nuevos

#### 1. Controlador
```
Origen: app/Http/Controllers/Parametros/InformeFormatoController.php
Destino: app/Http/Controllers/Parametros/InformeFormatoController.php
Tipo: CREAR (nuevo archivo)
Tamaño: ~3 KB
Métodos: index, create, store, show, edit, update, destroy
```

#### 2. Modelo
```
Origen: app/Models/InformeFormato.php
Destino: app/Models/InformeFormato.php
Tipo: CREAR (nuevo archivo)
Tamaño: ~1.5 KB
Relaciones: belongsTo Empresa
```

#### 3. Migraciones
```
a) 2026_06_16_000001_create_informe_formatos_table.php
   - Tabla: informe_formatos
   - Campos: nombre, codigo (unique), descripcion, blade_template, activo
   - Tamaño: ~1.2 KB

b) 2026_06_16_000002_add_informe_formato_to_empresas_table.php
   - Agrega: informe_formato_id (foreign key) en tabla empresas
   - Tamaño: ~1.1 KB
```

#### 4. Vistas (Blade)
```
a) resources/views/parametros/informe-formatos/index.blade.php
   - Listado de formatos con DataTable
   - Tamaño: ~3.5 KB

b) resources/views/parametros/informe-formatos/create.blade.php
   - Formulario crear/editar formato
   - Tamaño: ~2.8 KB

c) resources/views/parametros/informe-formatos/show.blade.php
   - Detalle del formato
   - Tamaño: ~2.2 KB
```

#### 5. Rutas (Agregadas a routes/parametros.php)
```php
Route::resource('informe-formatos', InformeFormatoController::class);
```

---

## 📝 ARCHIVOS MODIFICADOS (36)

### 1. CONTROLADORES (13 archivos)

#### Incidencias
```
a) app/Http/Controllers/Incidencias/ServicioController.php
   Cambios: Refactoring relación técnico (tecnicoResponsable → tecnico)
   Líneas: ~50 cambios
   Impacto: Alto - Afecta asignación de técnicos

b) app/Http/Controllers/Parametros/EmpresaController.php
   Cambios: Agregar informe_formato_id en create/edit/store/update
   Líneas: ~15 cambios
   Impacto: Medio - Nuevo campo en formulario

c) app/Http/Controllers/Parametros/EquipoController.php
   Cambios: Validaciones mejoradas, relación marca_id
   Líneas: ~30 cambios
   Impacto: Medio

d) app/Http/Controllers/Parametros/EquipoDocumentoController.php
   Cambios: Nuevos métodos para gestionar documentos
   Líneas: ~25 cambios
   Impacto: Bajo

e) app/Http/Controllers/Parametros/MantenimientoController.php
   Cambios: Mejoras en cálculo de próximas fechas
   Líneas: ~20 cambios
   Impacto: Bajo

f) app/Http/Controllers/Parametros/MantenimientoCalibrationController.php
   Cambios: Refactoring de calibraciones
   Líneas: ~18 cambios
   Impacto: Bajo
```

### 2. MODELOS (3 archivos)

#### Cambios Principales
```
a) app/Models/Empresa.php
   + Relación con InformeFormato
   + Campo informe_formato_id
   Líneas: ~5 cambios

b) app/Models/Equipo.php
   + Relación con Marca
   + Validaciones adicionales
   Líneas: ~10 cambios

c) app/Models/Servicio.php
   - Cambio de tecnicoResponsable a tecnico
   + Validaciones de estado
   Líneas: ~15 cambios
```

### 3. VISTAS (16 archivos)

#### Cambios Principales
```
a) resources/views/parametros/empresas/create.blade.php
   + Campo select para informe_formato_id
   Líneas: ~8 cambios

b) resources/views/parametros/empresas/edit.blade.php
   + Campo select para informe_formato_id
   Líneas: ~8 cambios

c) resources/views/parametros/equipos/create.blade.php
   + Marca (relación con tabla marcas)
   + Cliente y Sede mejorados
   Líneas: ~20 cambios

d) resources/views/parametros/equipos/index.blade.php
   + Columna Marca
   + Código activo cliente
   Líneas: ~15 cambios

e) resources/views/parametros/equipos/show.blade.php
   + Detalles de marca
   Líneas: ~10 cambios

f) resources/views/parametros/equipos/pdf.blade.php
   + Marca en PDF
   Líneas: ~8 cambios

g) resources/views/incidencias/servicios/index.blade.php
   + Cambio relación tecnico
   Líneas: ~10 cambios

h) resources/views/incidencias/servicios/show.blade.php
   + Cambio relación tecnico
   Líneas: ~8 cambios

i) resources/views/incidencias/servicios/attend.blade.php
   + Mejoras en UI
   Líneas: ~12 cambios

j) resources/views/incidencias/servicios/estadisticas.blade.php
   + Mejoras en gráficos
   Líneas: ~15 cambios

k) resources/views/incidencias/servicios/pdf/informe-tecnico-new.blade.php
   + Cambio relación tecnico
   Líneas: ~10 cambios

l) resources/views/layouts/app.blade.php
   + Link a "Informe Formatos" en menú
   Líneas: ~8 cambios
```

### 4. ROUTES (1 archivo)

```
routes/parametros.php
+ Route::resource('informe-formatos', InformeFormatoController::class);
Líneas: ~5 cambios
```

### 5. DATABASE

#### Migraciones (3 nuevas)
```
database/migrations/2026_06_16_000001_create_informe_formatos_table.php
database/migrations/2026_06_16_000002_add_informe_formato_to_empresas_table.php
database/migrations/2026_06_26_000001_remove_legacy_fields_from_servicios_table.php
```

#### Factories & Seeders (2 archivos)
```
database/factories/ServicioFactory.php
- Actualizar para usar nuevo campo tecnico_id

database/seeders/ServicioSeeder.php
- Actualizar para usar nuevo campo tecnico_id
```

### 6. DOCUMENTACIÓN (1 archivo)

```
PROTOCOLO_CAMBIOS_SEGURIDAD.md
- Actualización de protocolo de cambios
```

---

## 📋 CHECKLIST ANTES DE SUBIR

### Pre-Producción
- [ ] Todos los cambios están en git (git status limpio)
- [ ] Las migraciones están creadas y testeadas localmente
- [ ] Las nuevas vistas fueron testeadas en desktop y mobile
- [ ] Las nuevas rutas están registradas en routes/parametros.php
- [ ] El modelo InformeFormato tiene permisos en la BD
- [ ] Se ejecutó `php artisan migrate --force` en local sin errores

### Procedimiento
1. [ ] Hacer backup de la BD en producción
   ```bash
   # En producción
   mysqldump -u usuario -p ceogestion > backup_2026_07_01.sql
   ```

2. [ ] Subir archivos vía FTP
   ```
   Archivos: 52 archivos totales
   Tamaño estimado: ~250 KB
   Tiempo estimado: 2-3 minutos
   ```

3. [ ] Ejecutar migraciones
   ```bash
   cd /ruta/produccion/CEOGestion
   php artisan migrate --force
   ```

4. [ ] Limpiar cachés
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

5. [ ] Testing manual
   - [ ] Login con admin@ceogestion.com
   - [ ] Ir a Parametros → Equipos
   - [ ] Ir a Parametros → Informe Formatos (NUEVO)
   - [ ] Crear nuevo formato de informe
   - [ ] Asignar formato a una empresa
   - [ ] Editar un servicio
   - [ ] Verificar que aparece el técnico correcto

---

## 📊 RESUMEN DE CAMBIOS POR CATEGORÍA

| Categoría | Cantidad | Criticidad |
|-----------|----------|-----------|
| Controladores | 6 | Media |
| Modelos | 3 | Alta |
| Vistas | 16 | Media |
| Rutas | 1 | Baja |
| Migraciones | 3 | Alta |
| Modelos nuevos | 1 | Alta |
| Controladores nuevos | 1 | Media |
| **TOTAL** | **52** | **Media-Alta** |

---

## 🔧 ROLLBACK (si algo falla)

```bash
# Revertir última migración
php artisan migrate:rollback

# Restaurar BD desde backup
mysql -u usuario -p ceogestion < backup_2026_07_01.sql

# Restaurar archivos desde FTP backup
# (Contactar a proveedor de hosting)
```

---

## 📞 NOTAS IMPORTANTES

1. **Cambio crítico en Servicio**: La relación `tecnicoResponsable` cambió a `tecnico`
   - Necesario recompilar vistas con `php artisan view:clear`

2. **Nueva tabla `informe_formatos`**: Requerida para gestionar formatos por empresa
   - Debe ejecutarse ANTES de las demás migraciones

3. **Campos removidos en `servicios`**: 
   - `tecnico_asignado_id` (deprecated)
   - `tecnico_cedula` (deprecated)
   - Usa la migración 2026_06_26_* para removerlos

4. **Permisos necesarios**:
   - Nueva ruta: `parametros.informe-formatos.*`
   - Agregar en rol Admin o Coordinador

---

## 📅 Historial de Cambios

| Fecha | Cambio | Archivos |
|-------|--------|----------|
| 2026-05-27 | Última carga a producción | Marcas + Equipos |
| 2026-06-16 | Módulo Informe Formatos | +3 archivos nuevos |
| 2026-06-26 | Limpieza campos legacy | Migraciones |
| 2026-07-01 | Este documento | Resumen final |

---

**Generado:** 2026-07-01 | **Responsable:** Deployment Team | **Estado:** LISTO PARA PRODUCCIÓN ✅
