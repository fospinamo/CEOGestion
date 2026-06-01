# 📦 ARCHIVOS PARA COPIAR A PRODUCCIÓN (FTP)
## Fecha: 2026-05-27 | BD: ✅ ACTUALIZADA
## Cambios: Marcas + Cliente_ID + Sede_ID + Serial Único

---

## 📋 RESUMEN RÁPIDO

**Estado:** Base de datos ✅ ACTUALIZADA
**Próximo paso:** Copiar archivos PHP/Blade a producción via FTP

**Total archivos:** 10
- ✅ 3 NUEVOS
- 📝 7 MODIFICADOS

---

## 📂 RUTA CORRECTA EN FTP

**Destino base:** `/htdocs/CEOGestion/`

```
CEOGestion/ (raíz del proyecto)
│
├── app/
├── resources/
├── routes/
└── ... otros
```

---

## ✨ ARCHIVOS NUEVOS (3)

### 1️⃣ Model - NUEVA
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\app\Models\Marca.php

Destino Producción (FTP):
  app/Models/Marca.php

Tamaño: ~2 KB
Tipo: PHP Model
Acción: CREAR (nuevo archivo)
```

### 2️⃣ Controller - NUEVO
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\MarcaController.php

Destino Producción (FTP):
  app/Http/Controllers/Parametros/MarcaController.php

Tamaño: ~8 KB
Tipo: PHP Controller
Acción: CREAR (nuevo archivo)
```

### 3️⃣ Vista Index - NUEVA
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\index.blade.php

Destino Producción (FTP):
  resources/views/parametros/marcas/index.blade.php

Tamaño: ~4 KB
Tipo: Blade Template
Acción: CREAR (nueva carpeta + archivo)
```

### 4️⃣ Vista Create/Edit - NUEVA
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\create.blade.php

Destino Producción (FTP):
  resources/views/parametros/marcas/create.blade.php

Tamaño: ~3 KB
Tipo: Blade Template
Acción: CREAR
```

### 5️⃣ Vista Show - NUEVA
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\show.blade.php

Destino Producción (FTP):
  resources/views/parametros/marcas/show.blade.php

Tamaño: ~4 KB
Tipo: Blade Template
Acción: CREAR
```

---

## 📝 ARCHIVOS MODIFICADOS (7)

### 1️⃣ Model Equipo
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\app\Models\Equipo.php

Destino Producción (FTP):
  app/Models/Equipo.php

Cambios:
  ✅ Agregada relación: marca() belongsTo(Marca::class, 'marca_id')
  ✅ Actualizado fillable: marca_id, cliente_id, sede_id
  ✅ Removidos: 'marca' (string), 'codigo_interno'
  ✅ Renombrado: codigo_activo_cliente

Acción: REEMPLAZAR (archivo existente)
```

### 2️⃣ Controller Equipo
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\EquipoController.php

Destino Producción (FTP):
  app/Http/Controllers/Parametros/EquipoController.php

Cambios:
  ✅ Imports: Agregado 'use Marca;'
  ✅ create(): Pasa $marcas al view
  ✅ edit(): Pasa $marcas al view
  ✅ store(): Validación actualizada (marca_id, cliente_id, sede_id)
  ✅ update(): Validación actualizada

Acción: REEMPLAZAR (archivo existente)
```

### 3️⃣ Vista Crear/Editar Equipos
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\create.blade.php

Destino Producción (FTP):
  resources/views/parametros/equipos/create.blade.php

Cambios:
  ✅ Input: codigo_interno → codigo_activo_cliente
  ✅ Input: marca (text) → marca_id (select)
  ✅ Select NUEVO: cliente_id
  ✅ Select NUEVO: sede_id
  ✅ Nota: "Serial debe ser único"

Acción: REEMPLAZAR (archivo existente)
```

### 4️⃣ Vista Listar Equipos
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\index.blade.php

Destino Producción (FTP):
  resources/views/parametros/equipos/index.blade.php

Cambios:
  ✅ Columna: codigo_interno → codigo_activo_cliente
  ✅ Columna: marca (string) → marca?->nombre

Acción: REEMPLAZAR (archivo existente)
```

### 5️⃣ Vista Detalle Equipos
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\show.blade.php

Destino Producción (FTP):
  resources/views/parametros/equipos/show.blade.php

Cambios:
  ✅ Mostrar: codigo_activo_cliente en lugar de codigo_interno
  ✅ Mostrar: marca?->nombre en lugar de marca

Acción: REEMPLAZAR (archivo existente)
```

### 6️⃣ PDF Equipos
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\pdf.blade.php

Destino Producción (FTP):
  resources/views/parametros/equipos/pdf.blade.php

Cambios:
  ✅ Mostrar: codigo_activo_cliente
  ✅ Mostrar: marca?->nombre
  ✅ Fix: Typo 'serie' → 'serial'

Acción: REEMPLAZAR (archivo existente)
```

### 7️⃣ Rutas Parametros
```
Origen Local:
  c:\xampp\htdocs\CEOGestion\routes\parametros.php

Destino Producción (FTP):
  routes/parametros.php

Cambios:
  ✅ Agregado import: use App\Http\Controllers\Parametros\MarcaController;
  ✅ Agregada ruta: Route::resource('marcas', MarcaController::class);

Acción: REEMPLAZAR (archivo existente)
```

---

## 🚀 INSTRUCCIONES FTP

### Herramientas Recomendadas
- FileZilla (gratuita)
- WinSCP (gratuita)
- cPanel File Manager (web)

### Paso 1: Conectar FTP
```
Servidor: [tu_hosting]
Usuario: [ftp_user]
Contraseña: [ftp_password]
Puerto: 21 (o 22 si SFTP)

Conectar → Navegar a: htdocs/CEOGestion/
```

### Paso 2: Crear Carpeta Nueva (si no existe)
```
Ir a: resources/views/parametros/
Crear carpeta: marcas
Permisos: 755
```

### Paso 3: Subir Archivos NUEVOS
```
Desde tu computadora → Arrastrar a FTP

Archivos (5):
  ✅ app/Models/Marca.php
  ✅ app/Http/Controllers/Parametros/MarcaController.php
  ✅ resources/views/parametros/marcas/index.blade.php
  ✅ resources/views/parametros/marcas/create.blade.php
  ✅ resources/views/parametros/marcas/show.blade.php

Permisos: 644 (archivos)
```

### Paso 4: Actualizar Archivos MODIFICADOS
```
Desde tu computadora → Reemplazar en FTP

Archivos (7):
  ✅ app/Models/Equipo.php
  ✅ app/Http/Controllers/Parametros/EquipoController.php
  ✅ resources/views/parametros/equipos/create.blade.php
  ✅ resources/views/parametros/equipos/index.blade.php
  ✅ resources/views/parametros/equipos/show.blade.php
  ✅ resources/views/parametros/equipos/pdf.blade.php
  ✅ routes/parametros.php

Permisos: 644 (archivos)
```

### Verificar Permisos en FTP
```
Clic derecho en archivo → Permisos → 644
Clic derecho en carpeta → Permisos → 755
```

---

## ✅ CHECKLIST FTP

### Antes de Subir
- [ ] Conectado a FTP producción
- [ ] Backup de archivos antiguos realizado
- [ ] Archivos locales listos
- [ ] Permisos correctos (644, 755)

### Subida NUEVOS
- [ ] app/Models/Marca.php → Creado
- [ ] MarcaController.php → Creado
- [ ] Carpeta marcas/ → Creada
- [ ] Vista index → Creada
- [ ] Vista create → Creada
- [ ] Vista show → Creada

### Actualización MODIFICADOS
- [ ] Equipo.php → Reemplazado
- [ ] EquipoController.php → Reemplazado
- [ ] equipos/create.blade.php → Reemplazado
- [ ] equipos/index.blade.php → Reemplazado
- [ ] equipos/show.blade.php → Reemplazado
- [ ] equipos/pdf.blade.php → Reemplazado
- [ ] parametros.php → Reemplazado

### Después de Subir
- [ ] Permisos verificados (644/755)
- [ ] Acceso a aplicación OK
- [ ] Cache Laravel limpio (si tienes acceso)
- [ ] Logs sin errores

---

## 📊 RESUMEN DE CAMBIOS

| Tipo | Cantidad | Archivos |
|------|----------|----------|
| 🆕 Nuevos | 3 | Marca model, MarcaController, 3 vistas marcas |
| 📝 Modificados | 7 | 1 model, 1 controller, 4 vistas, 1 rutas |
| 📁 Carpetas nuevas | 1 | resources/views/parametros/marcas/ |
| 🗑️ Eliminados | 0 | Ninguno |
| **TOTAL** | **10 archivos** | |

---

## 🔐 SEGURIDAD

✅ **Antes de subir:**
- Verificar que BD está actualizada (Paso 1: Crear tabla marcas)
- Backup de archivos antiguos
- Horario de bajo uso en producción

✅ **Permisos (IMPORTANTE):**
- Archivos PHP: **644** (rw-r--r--)
- Carpetas: **755** (rwxr-xr-x)

✅ **En caso de error:**
- Restaurar archivos desde backup
- Revertir cambios en FTP
- Verificar logs Laravel

---

## 📞 VERIFICACIÓN RÁPIDA POST-FTP

### Acceder a Producción
```
URL: https://[dominio]/
Login: credenciales admin
```

### Tests Rápidos
```
1. Ir a: Parámetros → Equipos
   ✅ Debe cargar lista sin errores
   ✅ Columna "Código" debe mostrar codigo_activo_cliente
   ✅ Columna "Marca" debe mostrar nombre (no NULL)

2. Ir a: Parámetros → Marcas (NUEVA)
   ✅ Debe existir página
   ✅ Mostrar tabla de marcas
   ✅ Botón "Nueva Marca" funciona

3. Crear equipo nuevo
   ✅ Campos cliente_id y sede_id existen
   ✅ Marca es SELECT (no input text)
   ✅ Guardar funciona

4. Ver logs
   ✅ storage/logs/laravel.log sin ErrorException
```

---

## 📋 CHECKLIST FINAL

- [ ] BD actualizada (SQL ejecutado)
- [ ] Archivos descargados localmente
- [ ] FTP conectado
- [ ] 5 archivos NUEVOS subidos
- [ ] 7 archivos MODIFICADOS actualizados
- [ ] Permisos verificados (644/755)
- [ ] Tests básicos OK
- [ ] Logs sin errores
- [ ] Registro de cambios guardado (REGISTRO_EJECUCION_2026_05_27.md)

---

**Estado:** 🟢 LISTO PARA COPIAR
**BD:** ✅ ACTUALIZADA
**Archivos:** ✅ LOCALES LISTOS

**¡Proceder con FTP!** 📤
