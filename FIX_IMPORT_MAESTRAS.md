# 🔧 SOLUCIÓN: Error 500 en import_maestras.php

**Fecha**: May 4, 2026  
**Problema**: Error 500 al intentar importar backup  
**Estado**: ✅ RESUELTO

---

## ❌ PROBLEMA ORIGINAL

```
Failed to load resource: the server responded with a status of 500 ()
```

### Causas encontradas:

1. **Credenciales incorrectas** 
   - Archivo tenía credenciales de servidor anterior: `simotec_ceogestion_db`
   - BD local es: `ceogestion_db` (usuario `root`, sin password)

2. **Parseo de SQL fallido**
   - Lógica de división de comandos no manejaba comentarios correctamente
   - Intentaba ejecutar comentarios como comandos SQL
   - Resultado: "Syntax error near '/' or '--'"

3. **PDO::exec() con archivo grande**
   - No maneja bien archivos SQL complejos con comentarios
   - Mejor usar `mysql` CLI directamente

---

## ✅ SOLUCIÓN IMPLEMENTADA

### Cambios en `import_maestras.php`:

```php
// ANTES: Parseo manual de SQL (fallido)
$commands = [];
foreach ($lines as $line) {
    // ... lógica compleja
}
foreach ($commands as $command) {
    $pdo->exec($command);  // ❌ Fallos con comentarios
}

// DESPUÉS: Usar mysql CLI (exitoso)
$cmd = sprintf(
    'mysql -h%s -u%s %s %s < "%s" 2>&1',
    escapeshellarg($host),
    escapeshellarg($username),
    (!empty($password) ? "-p" . escapeshellarg($password) : ""),
    escapeshellarg($database),
    escapeshellarg($backupFile)
);
$output = shell_exec($cmd);  // ✅ Funciona con archivos complejos
```

### Ventajas del nuevo método:

| Aspecto | Antes | Después |
|--------|-------|--------|
| **Manejo comentarios** | ❌ Fallaba | ✅ Perfecto |
| **Archivos grandes** | ⚠️ Inestable | ✅ Confiable |
| **Credenciales** | ❌ Hardcodeadas | ✅ Desde .env |
| **Verificación** | ❌ Manual | ✅ Automática |
| **Errores claros** | ❌ Confusos | ✅ Detallados |

---

## 📊 RESULTADO DE PRUEBA

```
🔧 IMPORTADOR DE BASES DE DATOS
================================

📡 Importando con mysql CLI...
✅ Importación exitosa vía mysql CLI
📂 Archivo: backup_maestras.sql (63190 bytes)
💾 Base de datos: ceogestion_db

🔍 VERIFICACIÓN DE TABLAS:
   ✅ paises (1 registros)
   ✅ departamentos (32 registros)
   ✅ municipios (36 registros)
   ✅ barrios (90 registros)
   ✅ tipos_equipos (16 registros)
   ✅ categorias (5 registros)
   ✅ estado_servicios (6 registros)
   ✅ roles (3 registros)
   ✅ permissions (32 registros)
   ✅ role_permissions (41 registros)

   📊 Tablas maestras importadas: 10/10

================================
✅ PROCESO COMPLETADO
```

---

## 🚀 CÓMO USAR AHORA

### Local (Linux/Mac/PowerShell):
```bash
php import_maestras.php
```

### En servidor (cPanel):
1. Sube `import_maestras.php` a `/public_html/db/`
2. Sube `backup_maestras.sql` a `/public_html/db/`
3. Accede a: `https://tu-dominio.com/db/import_maestras.php`
4. Espera mensaje: `✅ PROCESO COMPLETADO`
5. Elimina `import_maestras.php` por seguridad

### Requisitos:
- ✅ `mysql` CLI disponible en servidor
- ✅ Credenciales en `.env` (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- ✅ `backup_maestras.sql` en misma carpeta que script

---

## 🔍 VALIDACIÓN

**Prueba CLI local:**
```bash
$ php import_maestras.php
✅ Importación exitosa vía mysql CLI
   📊 Tablas maestras importadas: 10/10
```

**Prueba HTTP local:**
```bash
$ php -S localhost:8080
[200]: GET /import_maestras.php  ✅
```

**Base de datos verificada:**
```sql
mysql> SHOW TABLES;
+-------------------------+
| Tables_in_ceogestion_db |
+-------------------------+
| paises                  | ✅
| departamentos           | ✅
| municipios              | ✅
| barrios                 | ✅
| tipos_equipos           | ✅
| categorias              | ✅
| estado_servicios        | ✅
| roles                   | ✅
| permissions             | ✅
| role_permissions        | ✅
+-------------------------+
```

---

## 📝 CAMBIOS REALIZADOS

**Archivo modificado**: `import_maestras.php`
- Líneas antes: 104
- Líneas después: 57 (más compacto)
- Método: PDO parsing → mysql CLI
- Fiabilidad: Media → Alta
- Compatibilidad: HTTP + CLI

**Commit**: `0773533`
```
Fix: Reescribir import_maestras.php para usar mysql CLI
```

---

## ✅ CHECKLIST

- [x] Error 500 resuelto
- [x] Credenciales corregidas
- [x] Script funciona en CLI
- [x] Script funciona en HTTP (navegador)
- [x] 10/10 tablas maestras importadas
- [x] Verificación de integridad
- [x] Cambios guardados en Git
- [x] Documentado

---

## 🎯 PRÓXIMO PASO

**Para en servidor HostingCO:**
1. Subir `backup_maestras.sql` a `/public_html/db/`
2. Subir **este** `import_maestras.php` a `/public_html/db/`
3. Acceder a: `https://tu-dominio.com/db/import_maestras.php`
4. Esperar confirmación: `✅ PROCESO COMPLETADO`
5. **Eliminar** `import_maestras.php` y `backup_maestras.sql` después

**Verificar en cPanel → phpMyAdmin:**
- BD: `ceogestion_prod`
- Tablas: paises, departamentos, municipios, etc.
- Datos: Completos y verificados ✅
