# 📤 GUÍA RÁPIDA - FTP A PRODUCCIÓN
## 2026-05-27 | BD: ✅ Actualizada | Código: 📦 Listo

---

## 🎯 ACCIONES NECESARIAS

```
✅ PASO 1: BD (YA COMPLETADO)
   Ejecutar SQL → Tabla marcas + Migraciones ✅

⏳ PASO 2: FTP (HACER AHORA)
   Subir 10 archivos → 3 nuevos + 7 modificados

🔍 PASO 3: TESTING
   Validar en navegador (Parametros → Equipos/Marcas)

✅ PASO 4: CIERRE
   Guardar registro final
```

---

## 📁 ARCHIVOS A COPIAR

### 🆕 NUEVOS (Crear en producción)

| Archivo | Origen Local | Destino FTP | Acción |
|---------|--------------|-------------|--------|
| 1 | `app\Models\Marca.php` | `app/Models/Marca.php` | CREAR |
| 2 | `app\Http\Controllers\Parametros\MarcaController.php` | `app/Http/Controllers/Parametros/MarcaController.php` | CREAR |
| 3 | `resources\views\parametros\marcas\index.blade.php` | `resources/views/parametros/marcas/index.blade.php` | CREAR |
| 4 | `resources\views\parametros\marcas\create.blade.php` | `resources/views/parametros/marcas/create.blade.php` | CREAR |
| 5 | `resources\views\parametros\marcas\show.blade.php` | `resources/views/parametros/marcas/show.blade.php` | CREAR |

**Total:** 5 archivos nuevos

---

### 📝 MODIFICADOS (Reemplazar en producción)

| Archivo | Origen Local | Destino FTP | Cambios |
|---------|--------------|-------------|---------|
| 1 | `app\Models\Equipo.php` | `app/Models/Equipo.php` | +marca() relation, fillable actualizado |
| 2 | `app\Http\Controllers\Parametros\EquipoController.php` | `app/Http/Controllers/Parametros/EquipoController.php` | +marcas en create/edit, validaciones |
| 3 | `resources\views\parametros\equipos\create.blade.php` | `resources/views/parametros/equipos/create.blade.php` | +cliente_id, +sede_id, marca_id select |
| 4 | `resources\views\parametros\equipos\index.blade.php` | `resources/views/parametros/equipos/index.blade.php` | codigo_activo_cliente, marca.nombre |
| 5 | `resources\views\parametros\equipos\show.blade.php` | `resources/views/parametros/equipos/show.blade.php` | codigo_activo_cliente, marca.nombre |
| 6 | `resources\views\parametros\equipos\pdf.blade.php` | `resources/views/parametros/equipos/pdf.blade.php` | codigo_activo_cliente, marca.nombre |
| 7 | `routes\parametros.php` | `routes/parametros.php` | +Marca routes |

**Total:** 7 archivos modificados

---

## 🚀 INSTRUCCIONES FTP RÁPIDAS

### 1. Conectar FTP
```
Herramienta: FileZilla, WinSCP, o cPanel
Servidor: [tu_hosting]
Navegar a: htdocs/CEOGestion/
```

### 2. Crear Carpeta (si no existe)
```
Ruta: resources/views/parametros/
Crear: marcas/
Permisos: 755
```

### 3. Subir Archivos NUEVOS (5 archivos)
```
Origen                                  → Destino
app\Models\Marca.php                   → app/Models/
MarcaController.php                    → app/Http/Controllers/Parametros/
marcas\index.blade.php                 → resources/views/parametros/marcas/
marcas\create.blade.php                → resources/views/parametros/marcas/
marcas\show.blade.php                  → resources/views/parametros/marcas/

Permisos: 644
```

### 4. Actualizar Archivos MODIFICADOS (7 archivos)
```
Origen                                           → Destino (REEMPLAZAR)
app\Models\Equipo.php                          → app/Models/Equipo.php
EquipoController.php                           → app/Http/Controllers/Parametros/
equipos\create.blade.php                       → resources/views/parametros/equipos/
equipos\index.blade.php                        → resources/views/parametros/equipos/
equipos\show.blade.php                         → resources/views/parametros/equipos/
equipos\pdf.blade.php                          → resources/views/parametros/equipos/
parametros.php                                 → routes/

Permisos: 644
```

---

## ✅ CHECKLIST FTP

```
ANTES
  [ ] Conectado a FTP
  [ ] Backup de archivos antiguos
  [ ] Horario bajo producción

NUEVOS (5)
  [ ] app/Models/Marca.php
  [ ] app/Http/Controllers/Parametros/MarcaController.php
  [ ] resources/views/parametros/marcas/index.blade.php
  [ ] resources/views/parametros/marcas/create.blade.php
  [ ] resources/views/parametros/marcas/show.blade.php

MODIFICADOS (7)
  [ ] app/Models/Equipo.php
  [ ] app/Http/Controllers/Parametros/EquipoController.php
  [ ] resources/views/parametros/equipos/create.blade.php
  [ ] resources/views/parametros/equipos/index.blade.php
  [ ] resources/views/parametros/equipos/show.blade.php
  [ ] resources/views/parametros/equipos/pdf.blade.php
  [ ] routes/parametros.php

DESPUÉS
  [ ] Permisos OK (644/755)
  [ ] Acceso a app OK
  [ ] Ejecutar tests
  [ ] Logs sin errores
```

---

## 🧪 TESTS RÁPIDOS EN NAVEGADOR

```
1. Ir a: /parametros/equipos
   ✅ Lista equipos sin errores
   ✅ Columnas: Código, Marca, Estado
   
2. Hacer Click en Equipo
   ✅ Mostrar detalle
   ✅ Ver marca.nombre

3. Ir a: /parametros/marcas
   ✅ NUEVA página funciona
   ✅ Mostrar tabla de marcas
   ✅ Botón "Nueva Marca" OK

4. Click "Nuevo Equipo"
   ✅ Formulario carga
   ✅ Select "Marca" (no input text)
   ✅ Campo "Cliente" existe
   ✅ Campo "Sede" existe

5. Llenar y guardar
   ✅ Equipo se crea
   ✅ Aparece en listado
   ✅ marca_id guardado
```

---

## 📞 REFERENCIA DOCUMENTOS

| Necesidad | Documento |
|-----------|-----------|
| Listar archivos FTP | **ARCHIVOS_FTP_PRODUCCION_2026_05_27.md** ← Detallado |
| Registro ejecución | **REGISTRO_EJECUCION_2026_05_27.md** |
| Tests completos | **DEPLOYMENT_GUIDE_2026_05_25.md** |
| Checklist práctico | **CHECKLIST_EJECUCION_2026_05_25.md** |

---

## 🟢 RESUMEN ESTADO

**BD:** ✅ LISTA (9 pasos completados)
**Código:** ✅ LISTO (10 archivos preparados)
**Próximo:** 📤 COPIAR VIA FTP (⏱️ 10 minutos)

---

**¡Listo para producción!** 🚀
