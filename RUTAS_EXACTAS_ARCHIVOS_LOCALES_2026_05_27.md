# 📋 RUTAS EXACTAS DE ARCHIVOS LOCALES
## 2026-05-27 | Para copiar a producción

---

## 🆕 ARCHIVOS NUEVOS (5)

### 1. Modelo Marca
```
Local: c:\xampp\htdocs\CEOGestion\app\Models\Marca.php
FTP:   app/Models/Marca.php
```

### 2. Controller Marcas
```
Local: c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\MarcaController.php
FTP:   app/Http/Controllers/Parametros/MarcaController.php
```

### 3. Vista Marcas - Index
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\index.blade.php
FTP:   resources/views/parametros/marcas/index.blade.php
```

### 4. Vista Marcas - Create/Edit
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\create.blade.php
FTP:   resources/views/parametros/marcas/create.blade.php
```

### 5. Vista Marcas - Show
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\show.blade.php
FTP:   resources/views/parametros/marcas/show.blade.php
```

---

## 📝 ARCHIVOS MODIFICADOS (6)

### 1. Modelo Equipo
```
Local: c:\xampp\htdocs\CEOGestion\app\Models\Equipo.php
FTP:   app/Models/Equipo.php (REEMPLAZAR)
```

### 2. Controller Equipos
```
Local: c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\EquipoController.php
FTP:   app/Http/Controllers/Parametros/EquipoController.php (REEMPLAZAR)
```

### 3. Vista Equipos - Create
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\create.blade.php
FTP:   resources/views/parametros/equipos/create.blade.php (REEMPLAZAR)
```

### 4. Vista Equipos - Index
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\index.blade.php
FTP:   resources/views/parametros/equipos/index.blade.php (REEMPLAZAR)
```

### 5. Vista Equipos - Show
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\show.blade.php
FTP:   resources/views/parametros/equipos/show.blade.php (REEMPLAZAR)
```

### 6. Layout Principal
```
Local: c:\xampp\htdocs\CEOGestion\resources\views\layouts\app.blade.php
FTP:   resources/views/layouts/app.blade.php (REEMPLAZAR)
```

---

## 🗂️ CARPETA NUEVA EN FTP

```
Crear:    resources/views/parametros/marcas/
Permisos: 755
```

---

## 📝 ARCHIVOS SQL

### SQL Permisos
```
Local: c:\xampp\htdocs\CEOGestion\PRODUCCION_SQL_PERMISOS_MARCAS.sql
Destino: phpMyAdmin → SQL Tab → Copy/Paste
```

### SQL Original
```
Local: c:\xampp\htdocs\CEOGestion\SQL_AGREGAR_PERMISOS_MARCAS.sql
(Referencia - contiene instrucciones en comentarios)
```

---

## 📚 DOCUMENTACIÓN DE REFERENCIA

```
c:\xampp\htdocs\CEOGestion\RESUMEN_FINAL_ARCHIVOS_SQL_2026_05_27.md ← EMPEZAR AQUÍ
c:\xampp\htdocs\CEOGestion\PRODUCCION_ARCHIVOS_SENTENCIAS_2026_05_27.md
c:\xampp\htdocs\CEOGestion\ARCHIVOS_FTP_PRODUCCION_2026_05_27.md
c:\xampp\htdocs\CEOGestion\GUIA_RAPIDA_FTP_2026_05_27.md
c:\xampp\htdocs\CEOGestion\VERIFICACION_POST_FTP_2026_05_27.md
```

---

## 🚀 INSTRUCCIONES RÁPIDAS

### Paso 1: Ejecutar SQL (2 min)
1. Abrir: PRODUCCION_SQL_PERMISOS_MARCAS.sql
2. Copy todo
3. phpMyAdmin → SQL → Paste → Ejecutar
4. Verificación: Debe mostrar 4, 4

### Paso 2: Subir Archivos FTP (10 min)
1. Conectar FTP a producción
2. Crear carpeta: resources/views/parametros/marcas/
3. Subir 5 archivos NUEVOS
4. Reemplazar 6 archivos MODIFICADOS
5. Verificar permisos: 644, 755

### Paso 3: Testing (5 min)
1. Recargar navegador: Ctrl+Shift+R
2. Logout/Login
3. Ir a: Parámetros → Marcas
4. Verificar funcionamiento

---

## ✅ CHECKLIST FTP (COPIAR ESTOS PATHS)

### Nuevos (Copy)
- [ ] c:\xampp\htdocs\CEOGestion\app\Models\Marca.php → app/Models/
- [ ] c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\MarcaController.php → app/Http/Controllers/Parametros/
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\index.blade.php → resources/views/parametros/marcas/
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\create.blade.php → resources/views/parametros/marcas/
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\show.blade.php → resources/views/parametros/marcas/

### Modificados (Reemplazar)
- [ ] c:\xampp\htdocs\CEOGestion\app\Models\Equipo.php → app/Models/ (REPLACE)
- [ ] c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\EquipoController.php → app/Http/Controllers/Parametros/ (REPLACE)
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\create.blade.php → resources/views/parametros/equipos/ (REPLACE)
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\index.blade.php → resources/views/parametros/equipos/ (REPLACE)
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\show.blade.php → resources/views/parametros/equipos/ (REPLACE)
- [ ] c:\xampp\htdocs\CEOGestion\resources\views\layouts\app.blade.php → resources/views/ (REPLACE)

---

## 🎯 ESTADO FINAL

✅ 11 archivos locales listos
✅ 2 SQL probados y listos
✅ 1 carpeta nueva para crear
✅ Documentación completa
✅ Instrucciones claras

**LISTO PARA PRODUCCIÓN** 🚀

