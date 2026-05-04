# 📋 CHECKLIST PRODUCCIÓN - FASE BACKUP E IMPORTACIÓN

**Estado**: 🟡 EN PROGRESO  
**Última actualización**: $(date)  
**Responsable**: Usuario  

---

## FASE 1: PREPARACIÓN LOCAL ✅

- [x] Backup maestras creado: `backup_maestras.sql` (61.71 KB)
  - ✓ Tablas: paises, departamentos, municipios, barrios, tipos_equipos, categorias, estado_servicios, roles, permissions, role_permissions
  - ✓ Sin datos operacionales (servicios, equipos, empresas, clientes, sedes, areas, usuarios)

- [x] Script de importación creado: `import_maestras.php`
  - ✓ Valida conexión a BD
  - ✓ Ejecuta comandos SQL
  - ✓ Reporte de resultados
  - ✓ Verificación de tablas

- [x] Documentación completada: `BACKUP_IMPORTACION.md`
  - ✓ 7 fases claramente documentadas
  - ✓ Pasos en cPanel
  - ✓ Troubleshooting
  - ✓ Checklist de verificación

---

## FASE 2: SUBIDA A SERVIDOR 🟡

**⏳ POR HACER - Siguiente paso:**

- [ ] Subir `backup_maestras.sql` a `/public_html/db/`
- [ ] Subir `import_maestras.php` a `/public_html/db/`

**Cómo:**
1. cPanel → File Manager
2. Navigate to: `/public_html/`
3. Create folder: `db/`
4. Upload 2 archivos

---

## FASE 3: IMPORTACIÓN EN SERVIDOR 🟡

**⏳ POR HACER - Después de subir archivos:**

**Opción A (Recomendada):**
- [ ] Accede a: `https://tu-dominio.com/db/import_maestras.php`
- [ ] Espera a completar (verás checkmarks)
- [ ] Verifica: `✅ IMPORTACIÓN COMPLETADA`
- [ ] Elimina: `import_maestras.php` por seguridad

**Opción B (si no funciona A):**
- [ ] cPanel → phpMyAdmin
- [ ] Select BD: `ceogestion_prod`
- [ ] Import → `backup_maestras.sql`

**Opción C (último recurso):**
- [ ] Crear ticket soporte
- [ ] Archivo: `/public_html/db/backup_maestras.sql`
- [ ] Indicar BD destino: `ceogestion_prod`

---

## FASE 4: VERIFICACIÓN 🟡

**⏳ POR HACER - Después de importar:**

Abre cPanel → phpMyAdmin → BD `ceogestion_prod`:

**Tablas que DEBEN existir y no estar vacías:**
- [ ] `paises` (1 registro)
- [ ] `departamentos` (X registros)
- [ ] `municipios` (X registros)
- [ ] `barrios` (X registros)
- [ ] `tipos_equipos` (X registros)
- [ ] `categorias` (X registros)
- [ ] `estado_servicios` (4 registros: Abierto, Completado, etc)
- [ ] `roles` (3: Admin, Técnico, Agente)
- [ ] `permissions` (múltiples)
- [ ] `role_permissions` (múltiples)

**Tablas que NO deben existir (operacionales):**
- [ ] ✓ servicios (NO DEBE EXISTIR)
- [ ] ✓ equipos (NO DEBE EXISTIR)
- [ ] ✓ empresas (NO DEBE EXISTIR)
- [ ] ✓ clientes (NO DEBE EXISTIR)
- [ ] ✓ sedes (NO DEBE EXISTIR)
- [ ] ✓ areas (NO DEBE EXISTIR)
- [ ] ✓ usuarios (solo admin después)

---

## FASE 5: CONFIGURAR .env 🟡

**⏳ POR HACER - Después de verificar importación:**

En cPanel File Manager → `/public_html/.env`:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ceogestion_prod
DB_USERNAME=ceogestion_user  # Ver en: cPanel → MySQL Databases
DB_PASSWORD=tu_password      # Ver en: cPanel → MySQL Databases
```

**Guardar cambios**: Ctrl+S

---

## FASE 6: EJECUTAR MIGRACIONES 🟡

**⏳ POR HACER - Después de .env:**

**Contactar Soporte HostingCO:**

Crear ticket con:
```
Asunto: Ejecutar migraciones Laravel

Ejecutar en /public_html/:
php artisan migrate --force
php artisan db:seed --force
php artisan cache:clear
php artisan optimize

Validar después que aplicación carga en:
https://tu-dominio.com/
```

**Lo que hará:**
- ✓ Crear tabla `users` admin
- ✓ Crear tabla `servicios` (vacía)
- ✓ Crear tabla `equipos` (vacía)
- ✓ Etc. (migraciones pendientes)
- ✓ Limpiar caches

---

## FASE 7: VERIFICACIÓN FINAL 🟡

**⏳ POR HACER - Después de migraciones:**

- [ ] Accede a: `https://tu-dominio.com/`
- [ ] Login con: `admin@ceogestion.com` / `password123`
- [ ] Verifica:
  - [ ] Dashboard carga sin errores
  - [ ] Menú visible
  - [ ] BD conectada (sin errores 500)
  - [ ] Estadísticas de servicios cargadas

---

## NOTAS IMPORTANTES

### 🚨 ANTES DE EMPEZAR:
1. Backup local está listo ✅
2. Se suben solo **maestras**, NO datos operacionales
3. Usuarios existentes en local NO se transfieren
4. Se crea usuario `admin@ceogestion.com` en seed de producción
5. No hay equipos, servicios ni empresas en producción inicialmente

### 🔐 SEGURIDAD:
- Eliminar `import_maestras.php` después de usar
- Cambiar contraseña admin después de primer login
- Verificar permisos archivos (755 para directorios, 644 para archivos)

### 📞 CONTACTO SOPORTE:
- **Email**: support@hosting-co.com
- **cPanel**: https://cpanel.hosting-co.com/
- **Tickets**: Ver en cPanel

---

## PRÓXIMOS PASOS DESPUÉS DE ESTO

1. **Crear datos iniciales en producción:**
   - Empresas (si aplica)
   - Sedes/Clientes
   - Áreas
   - Usuarios técnicos/agentes

2. **Pruebas en producción:**
   - Crear servicios
   - Generar reportes PDF
   - Verificar permisos

3. **Backup periódico:**
   - Configurar cron job para `backup-db.sh`
   - Daily backups (retención 30 días)

4. **Monitoreo:**
   - Verificar logs: `/storage/logs/`
   - Monitorear rendimiento
   - Updates de Laravel/PHP cuando disponibles
