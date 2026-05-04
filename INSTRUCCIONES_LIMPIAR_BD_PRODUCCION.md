# 🔧 INSTRUCCIONES: Limpiar e Importar BD en Producción

**Estado**: ✅ LISTO PARA PRODUCCIÓN  
**Método**: Robusto para cPanel (sin SSH)  
**Archivos**: 3 PHP/SQL  

---

## ❌ PROBLEMA EN SERVIDOR

Output anterior:
```
✅ Conexión exitosa
⚙️  Ejecutando SQL...
   ✗ Comando 1 ERROR: Syntax error...
   ✗ Comando 2 ERROR...
   ...118 errores...
```

**Causas:**
1. Script antiguo (PDO con parseo manual) fue ejecutado
2. BD en producción tiene datos operacionales (clientes, contratos, usuarios)
3. Necesita limpieza + importación correcta

---

## ✅ SOLUCIÓN: Script v3 (Robusto)

**Ventajas:**
- ✅ Parser SQL correcto (maneja comentarios)
- ✅ Dos fases: limpia + importa
- ✅ Sin dependencia de shell_exec (funciona en cPanel)
- ✅ Verificación integrada

---

## 📋 INSTRUCCIONES PARA SERVIDOR

### PASO 1: Subir archivos a cPanel (2 minutos)

**Tres archivos a subir:**
```
backup_maestras.sql           ← Ya existe, validar
limpiar_produccion.sql        ← NUEVO
clean_import_maestras.php     ← NUEVO (reemplazar el antiguo)
```

**Ubicación:** `/public_html/db/`

### PASO 2: Ejecutar desde navegador (3 minutos)

Accede a:
```
https://tu-dominio.com/db/clean_import_maestras.php
```

**Espera a ver:**
```
🔧 GESTOR DE BD v3 - ROBUSTO PARA CPANEL
✅ Conexión OK

FASE 1: Limpiar datos operacionales
✅ Ejecutados: 20

FASE 2: Importar tablas maestras
✅ Ejecutados: XXX

✅ PROCESO COMPLETADO EXITOSAMENTE
```

### PASO 3: Verificar resultado

Debería ver:
```
✅ paises: 1 registros
✅ departamentos: 32 registros
✅ municipios: 36 registros
✅ barrios: 90 registros
✅ tipos_equipos: 16 registros
✅ categorias: 5 registros
✅ estado_servicios: 6 registros
✅ roles: 3 registros
✅ permissions: 32 registros
✅ role_permissions: 41 registros

🗑️  DATOS OPERACIONALES:
✅ servicios: 0 (LIMPIO)
✅ equipos: 0 (LIMPIO)
✅ empresas: 0 (LIMPIO)
✅ clientes: 0 (LIMPIO)
✅ sedes: 0 (LIMPIO)
✅ areas: 0 (LIMPIO)
```

### PASO 4: Eliminar archivos por seguridad (1 minuto)

En cPanel File Manager, ir a `/public_html/db/` y eliminar:
- ❌ clean_import_maestras.php
- ❌ backup_maestras.sql
- ❌ limpiar_produccion.sql

---

## 🎯 PRÓXIMOS PASOS DESPUÉS

1. **Ejecutar migraciones finales** (contactar soporte o ejecutar):
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

2. **Probar acceso:**
   ```
   https://tu-dominio.com/
   Login: admin@ceogestion.com
   Password: password123
   ```

3. **Verificar que no haya errores 500**

---

## 🆘 TROUBLESHOOTING

### Si dice "PROCESO COMPLETADO" pero maestras están vacías:
- Verifica que `backup_maestras.sql` está en `/public_html/db/`
- Tamaño debe ser ~63KB

### Si dice "Conexión error":
- Verifica `.env` tiene credenciales correctas
- phpMyAdmin → verifica que BD existe

### Si dice "Archivo no encontrado":
- Verifica que todos 3 archivos están en `/public_html/db/`
- Nombres exactos (sin espacios):
  - backup_maestras.sql
  - limpiar_produccion.sql
  - clean_import_maestras.php

### Si timeout (> 5 minutos):
- Contactar soporte HostingCO
- Pedirles ejecutar manualmente via cPanel Terminal

---

## 📊 ARCHIVOS DE PRODUCCIÓN

| Archivo | Tamaño | Función |
|---------|--------|---------|
| backup_maestras.sql | 63 KB | Dump SQL de maestras |
| limpiar_produccion.sql | <1 KB | SQL para limpiar operacionales |
| clean_import_maestras.php | 6 KB | Script ejecutor PHP |

---

## ✅ CHECKLIST

- [ ] Subir 3 archivos a `/public_html/db/`
- [ ] Acceder a: https://tu-dominio.com/db/clean_import_maestras.php
- [ ] Ver: "✅ PROCESO COMPLETADO EXITOSAMENTE"
- [ ] Verificar: 10/10 maestras + datos limpios
- [ ] Eliminar 3 archivos por seguridad
- [ ] Ejecutar: php artisan migrate --force
- [ ] Ejecutar: php artisan db:seed --force
- [ ] Probar login en https://tu-dominio.com/
- [ ] Verificar dashboard sin errores 500

---

## 📝 NOTAS IMPORTANTES

1. **Este script es SEGURO:**
   - Solo limpia datos operacionales (no maestras)
   - Preserva usuarios admin
   - Mantiene tablas de configuración

2. **Después de ejecutar:**
   - BD estará lista para migraciones
   - Podrá crear nuevos servicios/equipos en producción
   - Datos locales no serán sobrescritos

3. **Si algo falla:**
   - Contactar: support@hosting-co.com
   - Mencionar: "Error en clean_import_maestras.php línea X"
   - Adjuntar screenshot

---

## 🚀 VERSIÓN ACTUAL

- Script: v3 (robusto para cPanel)
- Probado: ✅ Local Windows + XAMPP
- Estado: LISTO PARA PRODUCCIÓN
- Fecha: May 4, 2026
