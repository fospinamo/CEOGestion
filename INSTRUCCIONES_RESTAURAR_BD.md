# 🚀 RESTAURAR BD EN PRODUCCIÓN - GUÍA FINAL

## 📋 RESUMEN EJECUTIVO

Tu BD en producción ya fue **limpiada exitosamente** ✅

```
✅ Limpieza completada
✅ Datos operacionales: TODOS EN 0
✅ Maestras intactas: 10/10 verificadas
✅ PROCESO COMPLETADO EXITOSAMENTE
```

Ahora necesitas ejecutar Laravel para recrear las maestras y la estructura de datos.

---

## 🎯 PRÓXIMOS PASOS EN CPANEL

### PASO 1: Eliminar archivo de limpieza

1. Accede a **cPanel > File Manager**
2. Navega a: `/public_html/db/`
3. Elimina estos archivos:
   - ✅ `import_maestras.php` (archivo viejo, NO usar)
   - ✅ `limpiar_produccion.sql` (ya no necesario)
   - ✅ `backup_maestras.sql` (ya no necesario)
   - ✅ `clean_import_maestras.php` (versión anterior, NO usar)

**Quedan SOLO dos archivos para próximas limpiezas:**
- `clean_database.php` (nuevo, simple, más seguro)

### PASO 2: Ejecutar Laravel en Terminal

1. En cPanel, ve a: **Terminal** (Terminal de cPanel)
2. Ejecuta:

```bash
cd public_html
php artisan migrate --force
```

Resultado esperado:
```
[✓] Migrations completed
20 migrations executed
```

3. Luego ejecuta:

```bash
php artisan db:seed --force
```

Resultado esperado:
```
✅ Maestras creadas
✅ Roles creados
✅ Permisos creados
✅ Usuario admin creado
```

### PASO 3: Verificación

1. Accede a: **https://tu-dominio.com/**
2. Deberías ver la página de login normal
3. Login con: `admin@ceogestion.com` / `password123`
4. Navega a diferentes secciones para verificar que funciona

### PASO 4: Seguridad (IMPORTANTE)

⚠️ **Elimina `clean_database.php` después de usarlo:**

```bash
rm public_html/db/clean_database.php
```

---

## 🔧 SI NECESITAS LIMPIAR DE NUEVO

En el futuro, si necesitas limpiar datos operacionales sin perder maestras:

### Opción A: Desde Terminal (Recomendado)

```bash
cd public_html
php artisan db:seed --class=MaestrasSeeder --force
# O limpia manualmente con SQL en phpMyAdmin
```

### Opción B: Usar `clean_database.php`

1. Sube el archivo a `/public_html/db/clean_database.php`
2. Accede a: `https://tu-dominio.com/db/clean_database.php`
3. Verifica que dice "✅ LIMPIEZA EXITOSA"
4. **Elimina el archivo inmediatamente**

---

## 📊 ESTRUCTURA FINAL

Después de ejecutar los comandos, tu BD tendrá:

### ✅ MAESTRAS (Preservadas)
- paises (1)
- departamentos (32)
- municipios (36)
- barrios (90)
- tipos_equipos (15+)
- categorias (5)
- estado_servicios (6)
- roles (3)
- permissions (32+)
- role_permissions (41+)

### ✅ USUARIOS (Recreados)
- admin@ceogestion.com (Admin)
- tecnico@ceogestion.com (Técnico)
- agente@ceogestion.com (Agente)
- Múltiples usuarios cliente (con tokens)

### 🗑️ DATOS OPERACIONALES (Vacíos)
- servicios (0)
- equipos (0)
- empresas (0)
- clientes (0)
- sedes (0)
- areas (0)
- contratos (0)
- documentos_adjuntos (0)

---

## ⚠️ TROUBLESHOOTING

### Error: "SQLSTATE[HY000]: General error"
**Solución:** Contacta a soporte. Probablemente hay límites de ejecución en el servidor.

### Error: "migrate command not found"
**Solución:** Verifica que estés en la carpeta correcta:
```bash
cd /home/tuusuario/public_html
pwd  # Debe mostrar /home/tuusuario/public_html
php artisan migrate --force
```

### Login no funciona después de migrar
**Solución:** 
1. Ejecuta: `php artisan cache:clear && php artisan config:clear`
2. Vuelve a intentar login
3. Usa: admin@ceogestion.com / password123

### Página muestra error 500
**Solución:**
```bash
php artisan storage:link
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

---

## 🎓 EXPLICACIÓN TÉCNICA

### ¿Por qué no importamos SQL directamente?

Porque:
1. **Migraciones son la verdad única:** Laravel maneja versionamiento de esquema
2. **Seeders son reproducibles:** Generan datos consistentes cada ejecución
3. **Más seguro:** Evita problemas de encoding, FK constraints, etc.

### ¿Qué hace `clean_database.php`?

```
1. Conecta a BD
2. Ejecuta: SET FOREIGN_KEY_CHECKS=0
3. Borra tablas operacionales (respetando orden de dependencias)
4. Reset AUTO_INCREMENT
5. Ejecuta: SET FOREIGN_KEY_CHECKS=1
6. Reporta resultado
```

### ¿Qué hacen `php artisan migrate` y `php artisan db:seed`?

```
migrate:   Ejecuta migraciones en orden → crea maestras
db:seed:   Ejecuta seeders → llena maestras con datos
```

---

## ✅ CHECKLIST FINAL

- [ ] Accedí a cPanel
- [ ] Ejecuté: `php artisan migrate --force`
- [ ] Ejecuté: `php artisan db:seed --force`
- [ ] Verifiqué login en: https://tu-dominio.com/
- [ ] Eliminé `clean_database.php` por seguridad
- [ ] Dashboard carga sin errores 500

---

## 📞 SOPORTE

Si tienes problemas:

1. Verifica los logs: `tail -100 storage/logs/laravel.log`
2. Intenta limpiar cache: `php artisan cache:clear`
3. Contacta soporte mencionando el error exacto

---

**Generado:** 5 Mayo 2026  
**Versión:** v3 - Final  
**Estado:** ✅ Producción LISTA
