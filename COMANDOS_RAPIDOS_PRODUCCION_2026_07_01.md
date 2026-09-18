# ⚡ COMANDOS RÁPIDOS - PRODUCCIÓN 2026-07-01

---

## 🚀 EJECUCIÓN RÁPIDA (Copy-Paste)

### En Producción vía SSH/Terminal:

```bash
# 1. BACKUP
mysqldump -u ceogestion_user -p ceogestion_db > backup_2026_07_01.sql
echo \"Backup completado: backup_2026_07_01.sql\"

# 2. MIGRACIONES
cd /home/usuario/public_html/CEOGestion  # Ajustar ruta según hosting
php artisan migrate --force

# 3. LIMPIAR CACHÉS
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. VERIFICAR
php artisan route:list | grep -i informe
echo \"✅ Deployment completado\"
```

---

## 📋 VERIFICACIONES RÁPIDAS

### Verificar Migraciones Ejecutadas
```bash
php artisan migrate:status
```

**Salida esperada:**
```
┌────┬────────────────────────────────────────────────┬────────────┐
│ Batch │ Migration │ Batch │
├────┼────────────────────────────────────────────────┼────────────┤
│ 1  │ 2026_06_16_000001_create_informe_formatos_table           │ 2026-07-01 │
│ 1  │ 2026_06_16_000002_add_informe_formato_to_empresas_table   │ 2026-07-01 │
│ 1  │ 2026_06_26_000001_remove_legacy_fields_from_servicios_table│ 2026-07-01 │
```

### Verificar Nuevas Rutas
```bash
php artisan route:list | grep informe-formatos
```

**Salida esperada:**
```
  parametros.informe-formatos.index     GET|HEAD       /parametros/informe-formatos
  parametros.informe-formatos.create    GET|HEAD       /parametros/informe-formatos/create
  parametros.informe-formatos.store     POST           /parametros/informe-formatos
  parametios.informe-formatos.show      GET|HEAD       /parametros/informe-formatos/{informe_formato}
  parametros.informe-formatos.edit      GET|HEAD       /parametros/informe-formatos/{informe_formato}/edit
  parametros.informe-formatos.update    PUT|PATCH      /parametros/informe-formatos/{informe_formato}
  parametros.informe-formatos.destroy   DELETE         /parametros/informe-formatos/{informe_formato}
```

### Verificar Nueva Tabla
```bash
php artisan tinker
>>> DB::table('informe_formatos')->count()
// Salida: 0 (tabla creada correctamente)
```

---

## 🔧 COMANDOS ÚTILES

### Si Algo Falla - Rollback
```bash
# Opción 1: Revertir última migración
php artisan migrate:rollback

# Opción 2: Revertir todo y reintentar
php artisan migrate:refresh
php artisan migrate
```

### Restaurar BD desde Backup
```bash
mysql -u usuario -p base_datos < backup_2026_07_01.sql
# Pedir contraseña y esperar a que complete
```

### Verificar Permisos de Carpetas
```bash
# Laravel necesita escribir en storage y bootstrap/cache
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Ver Logs en Tiempo Real
```bash
tail -f storage/logs/laravel.log
# Presionar Ctrl+C para salir
```

---

## 📊 QUERIES SQL ÚTILES

### Verificar Tabla Informe Formatos Creada
```sql
SHOW TABLES LIKE 'informe_formatos';
DESCRIBE informe_formatos;
```

### Verificar Columna en Empresas
```sql
SHOW COLUMNS FROM empresas WHERE Field = 'informe_formato_id';
```

### Verificar Que Campos Legacy Fueron Removidos
```sql
SHOW COLUMNS FROM servicios LIKE 'tecnico_asignado_id';
SHOW COLUMNS FROM servicios LIKE 'tecnico_cedula';
-- Deben retornar vacío (removed)
```

### Insertar un Formato de Prueba
```sql
INSERT INTO informe_formatos (nombre, codigo, descripcion, blade_template, activo, created_at, updated_at) 
VALUES (
    'Informe Técnico Estándar',
    'INF-001',
    'Formato de informe técnico estándar',
    'incidencias.servicios.pdf.informe-tecnico-new',
    1,
    NOW(),
    NOW()
);
```

### Contar Cambios
```sql
-- Empresas sin formato asignado aún
SELECT COUNT(*) FROM empresas WHERE informe_formato_id IS NULL;

-- Formatos creados
SELECT COUNT(*) FROM informe_formatos;

-- Servicios con técnico asignado
SELECT COUNT(*) FROM servicios WHERE tecnico_id IS NOT NULL;
```

---

## 🐛 TROUBLESHOOTING

### Error: \"Call to undefined method...\"
```bash
# Solución: Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Error: \"No such table...\"
```bash
# Solución: Ejecutar migraciones
php artisan migrate --force
```

### Error: \"Undefined variable...\"
```bash
# Solución: Limpiar vistas compiladas
php artisan view:clear
```

### Error: \"Class not found...\"
```bash
# Solución: Composer autoload
composer dump-autoload -o
```

### Error: \"Permission denied...\"
```bash
# Solución: Permisos en storage
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 📱 TESTING RÁPIDO EN NAVEGADOR

### URLs para Probar

```
✅ Login
https://[tu-dominio]/login

✅ Dashboard
https://[tu-dominio]/dashboard

✅ Equipos
https://[tu-dominio]/parametros/equipos

✅ Informe Formatos (NUEVO)
https://[tu-dominio]/parametros/informe-formatos

✅ Servicios
https://[tu-dominio]/incidencias/servicios

✅ Empresas
https://[tu-dominio]/parametros/empresas
```

### Verificación Manual

```
1. Ir a /parametros/informe-formatos
   - ¿Carga la página?
   - ¿Se ve el botón \"Nuevo Formato\"?
   - ¿La tabla está vacía?

2. Crear un nuevo formato
   - Nombre: \"Test\"
   - Código: \"TST-001\"
   - Template: \"incidencias.servicios.pdf.informe-tecnico-new\"
   - ¿Se guarda correctamente?

3. Editar una empresa
   - ¿Aparece el selector \"Formato de Informe\"?
   - ¿Puedo seleccionar el formato creado?
   - ¿Se guarda la relación?

4. Ver un servicio
   - ¿Aparece \"Técnico Asignado\" correctamente?
   - ¿No hay error \"undefined\"?
```

---

## 📊 MONITOREO POST-DEPLOYMENT

### Verificar Logs de Errores
```bash
# Buscar errores en las últimas 2 horas
grep -i error storage/logs/laravel.log | tail -50

# Contar errores por tipo
grep -i error storage/logs/laravel.log | cut -d: -f1 | sort | uniq -c
```

### Monitorear Performance
```bash
# Ver archivos más accedidos
tail -100 storage/logs/laravel.log | grep -o 'POST\\|GET\\|PUT\\|DELETE' | sort | uniq -c

# Ver tiempo de respuesta promedio
# (requiere logs detallados configurados)
```

### Verificar Uso de Base de Datos
```bash
# Tamaño de la BD
SELECT 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Tamaño (MB)'
FROM information_schema.tables
WHERE table_schema = 'ceogestion';

# Tabla más grande
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) 'Tamaño (MB)'
FROM information_schema.tables
WHERE table_schema = 'ceogestion'
ORDER BY (data_length + index_length) DESC
LIMIT 5;
```

---

## 🔐 SEGURIDAD POST-DEPLOYMENT

### Verificar Permisos
```bash
# storage/ debe ser escribible por web server
ls -la storage/
# Debe ser: drwxr-xr-x www-data www-data

# bootstrap/cache/ debe ser escribible
ls -la bootstrap/cache/
# Debe ser: drwxr-xr-x www-data www-data
```

### Verificar .env
```bash
# .env debe estar en raíz del proyecto (NO en git)
# .env.example debe tener la estructura

ls -la .env
ls -la .env.example

# Verificar que no hay credenciales en git
git log --all -p | grep -i \"APP_KEY\\|DB_PASSWORD\" | head -5
# Debe estar vacío o no tener valores sensibles
```

### Verificar Debug
```bash
# APP_DEBUG debe ser false en producción
grep APP_DEBUG .env
# Salida: APP_DEBUG=false
```

---

## ⏰ CHECKLIST POST-DEPLOYMENT

```bash
# Ejecutar estas líneas una por una:

echo \"🔍 1. Verificando migraciones...\"
php artisan migrate:status | grep -i \"2026_06_16\\|2026_06_26\" || echo \"⚠️ Migraciones no encontradas\"

echo \"🔍 2. Verificando rutas...\"
php artisan route:list | grep -c informe-formatos || echo \"⚠️ Rutas no encontradas\"

echo \"🔍 3. Verificando tabla informe_formatos...\"
mysql -u usuario -p base_datos -e \"SHOW TABLES LIKE 'informe_formatos';\" || echo \"⚠️ Tabla no existe\"

echo \"🔍 4. Verificando columna en empresas...\"
mysql -u usuario -p base_datos -e \"SHOW COLUMNS FROM empresas LIKE 'informe_formato_id';\" || echo \"⚠️ Columna no existe\"

echo \"🔍 5. Verificando logs...\"
tail -20 storage/logs/laravel.log | grep -i error && echo \"⚠️ Hay errores\" || echo \"✅ Sin errores\"

echo \"✅ Verificación completada\"
```

---

## 📞 SOPORTE RÁPIDO

### Si todo está roto
```bash
# 1. Revertir inmediatamente
php artisan migrate:rollback

# 2. Restaurar BD
mysql -u usuario -p base_datos < backup_2026_07_01.sql

# 3. Restaurar archivos (desde FTP backup)
# Usar cliente FTP para descargar /backup_2026_07_01/

# 4. Notificar al equipo
echo \"⚠️ ROLLBACK COMPLETADO - Contactar a desarrollo\"
```

### Si algo específico falla
```bash
# Obtener trace de errores
php artisan tinker
>>> Log::info('test');
>>> dd(DB::table('informe_formatos')->first());

# Ver último error exacto
tail -1 storage/logs/laravel.log
```

---

## 📚 REFERENCIAS RÁPIDAS

**Documentación completa:**
- [ARCHIVOS_PARA_PRODUCCION_2026_07_01.md](ARCHIVOS_PARA_PRODUCCION_2026_07_01.md)
- [LISTA_ARCHIVOS_FTP_2026_07_01.md](LISTA_ARCHIVOS_FTP_2026_07_01.md)
- [RESUMEN_EJECUTIVO_PRODUCCION_2026_07_01.md](RESUMEN_EJECUTIVO_PRODUCCION_2026_07_01.md)

**Guías del proyecto:**
- [COMIENZA_AQUI.md](COMIENZA_AQUI.md)
- [ESTADO_PROYECTO.md](ESTADO_PROYECTO.md)
- [BUENAS_PRACTICAS.md](BUENAS_PRACTICAS.md)

---

**Última actualización:** 2026-07-01  
**Estado:** LISTO PARA PRODUCCIÓN ✅  
**Tiempo estimado:** 10-15 minutos  

---

## 📝 NOTAS DE CAMBIOS

```
✅ Módulo Informe Formatos: NUEVO
✅ Refactoring técnico: COMPLETADO
✅ Limpieza campos legacy: PREPARADA
✅ Todas las validaciones: PASS
✅ Tests locales: PASS

⏳ Deployment en producción: PENDIENTE
⏳ Testing en producción: PENDIENTE
⏳ Notificación equipo: PENDIENTE
```

Guardar este archivo como referencia para deployment futuro.
