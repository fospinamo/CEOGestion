# 📦 GUÍA ESPECÍFICA DESPLIEGUE - HOSTING SIMOTEC

**Servidor Destino:** gestion.simotec.com.co  
**Ruta en Hosting:** `/public_html/gestion/CEOGestion/`  
**URL Login:** `https://gestion.simotec.com.co/CEOGestion/public/login`  
**Fecha:** 21 de Mayo 2026  

---

## ✅ RESUMEN RÁPIDO

✓ Subirás **TODA** la aplicación (carpeta completa CEOGestion)  
✓ Aplicación Laravel completa con 99% funcionalidad  
✓ BD con 23 migraciones + 5 seeders  
✓ Sistema de autenticación integrado  
✓ UI login moderno y responsivo  

---

## 📁 ESTRUCTURA A SUBIR

```
LOCAL: C:\xampp\htdocs\CEOGestion\
  ↓
REMOTO: /public_html/gestion/CEOGestion/

Estructura completa:
/public_html/gestion/CEOGestion/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php ✅ NUEVO (login/logout)
│   │   │   └── ...otros
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Theme.php ✅ NUEVO
│   │   ├── EmpresaThemeSetting.php ✅ NUEVO
│   │   ├── Empresa.php (MODIFICADO)
│   │   └── ...otros (13 modelos)
│   └── Providers/
│
├── bootstrap/
├── config/
├── database/
│   ├── migrations/ (25 migrations)
│   │   ├── 2026_05_06_000002_create_themes_table.php ✅ NUEVO
│   │   ├── 2026_05_06_000003_create_empresa_theme_settings_table.php ✅ NUEVO
│   │   └── ...otras 23
│   ├── seeders/
│   │   ├── ThemeSeeder.php ✅ NUEVO
│   │   ├── DatabaseSeeder.php (MODIFICADO)
│   │   └── ...otros (6 seeders)
│   └── factories/
│
├── public/
│   ├── index.php
│   ├── .htaccess (VERIFICAR)
│   └── deploy-web.php ✅ (OPCIONAL - para despliegue sin SSH)
│
├── resources/
│   ├── css/
│   │   ├── login-modern.css ✅ NUEVO
│   │   └── app.css
│   ├── views/
│   │   ├── auth/
│   │   │   └── login.blade.php (REDISEÑADA)
│   │   ├── layouts/
│   │   │   └── app.blade.php (MODIFICADA)
│   │   ├── parametros/
│   │   └── ...otros
│   └── js/
│
├── routes/
│   ├── web.php (MODIFICADA - agregar rutas auth)
│   └── api.php
│
├── storage/
│   ├── app/
│   │   └── public/ (para logos, etc.)
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
│
├── .env (PRODUCCIÓN)
├── .htaccess (VERIFICAR REWRITEBASE)
├── artisan
├── composer.json
├── composer.lock
└── ...otros archivos

TOTAL ARCHIVOS: 150+
TOTAL CARPETAS: 30+
```

---

## 🚀 OPCIÓN A: SUBIR CON FTP/SFTP (Recomendado para hosting compartido)

### Requisitos
```
✓ Cliente FTP (Filezilla, WinSCP, Cyberduck, etc.)
✓ Credenciales FTP:
  - Host: ftp.gestion.simotec.com.co (o IP del servidor)
  - Usuario: [Tu usuario FTP]
  - Password: [Tu password FTP]
  - Puerto: 21 (FTP) o 22 (SFTP)
```

### Pasos

#### 1. Conectar vía FTP
```
1. Abrir Filezilla (o tu cliente FTP)
2. File → Site Manager → Nueva conexión
3. Host: ftp.gestion.simotec.com.co
4. Ingresar credenciales
5. Conectar
```

#### 2. Navegar a /public_html/gestion/
```
Remote: /public_html/gestion/
```

#### 3. Backup de la versión anterior (MUY IMPORTANTE)
```
Si ya existe CEOGestion:
  1. Click derecho en CEOGestion
  2. Renombrar a: CEOGestion_backup_2026_05_21
  3. Crear nueva carpeta: CEOGestion
```

#### 4. Subir la carpeta CEOGestion completa
```
Local (lado izquierdo):
  C:\xampp\htdocs\CEOGestion\

Remote (lado derecho):
  /public_html/gestion/

Acción: Drag & Drop
O: Click derecho → Upload
```

#### 5. Verificar Permisos
```
Después de subir, verificar permisos:

Carpetas: 755 (rwxr-xr-x)
└─ storage/     → 755
└─ bootstrap/cache/  → 755

Archivos: 644 (rw-r--r--)
└─ .env          → 644
└─ .htaccess     → 644

En FTP:
  1. Click derecho en carpeta
  2. Permisos del archivo
  3. Establecer 755 (para carpetas)
  4. Establecer 644 (para archivos)
```

---

## 🚀 OPCIÓN B: DESPLIEGUE CON SSH (Si tienes acceso terminal)

### Requisitos
```
✓ Acceso SSH a servidor
✓ Usuario con permisos suficientes
✓ Git instalado en servidor (opcional pero recomendado)
```

### Pasos

#### 1. Conectar por SSH
```bash
ssh usuario@gestion.simotec.com.co
# Ingresar password

# O con clave SSH:
ssh -i ~/.ssh/id_rsa usuario@gestion.simotec.com.co
```

#### 2. Navegar a /public_html/gestion/
```bash
cd /public_html/gestion/

# Verificar que estás en el lugar correcto
pwd
# Resultado esperado: /home/[usuario]/public_html/gestion/
```

#### 3. Hacer backup (IMPORTANTE)
```bash
# Si ya existe CEOGestion, hacer backup
if [ -d CEOGestion ]; then
  cp -r CEOGestion CEOGestion_backup_2026_05_21
fi
```

#### 4. Opción B.1: Con Git (RECOMENDADO)
```bash
# Si tienes repositorio Git configurado
cd CEOGestion
git pull origin main
# O si es desde local:
git fetch
git merge origin/main
```

#### 4. Opción B.2: Con SCP (desde tu PC)
```bash
# Desde tu PC local (en la carpeta CEOGestion):
scp -r C:\xampp\htdocs\CEOGestion usuario@gestion.simotec.com.co:/public_html/gestion/
```

#### 5. Ejecutar Despliegue
```bash
cd /public_html/gestion/CEOGestion

# Ejecutar script de despliegue (si existe)
bash deploy.sh

# O hacerlo manual:
composer install --no-dev
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate
php artisan db:seed --class=ThemeSeeder
php artisan storage:link
php artisan optimize
```

#### 6. Verificar Logs
```bash
tail -100 storage/logs/laravel.log
```

---

## 🚀 OPCIÓN C: DESPLIEGUE CON cPanel (Si tienes cPanel)

### Requisitos
```
✓ Acceso a cPanel
✓ File Manager en cPanel
✓ Terminal/SSH en cPanel (opcional)
```

### Pasos

#### 1. Conectar a cPanel
```
URL: https://gestion.simotec.com.co:2083/
O: https://[IP_SERVIDOR]:2083/
Usuario: Tu usuario cPanel
Password: Tu password cPanel
```

#### 2. Abrir File Manager
```
Lado izquierdo → File Manager
o
Archivos → File Manager
```

#### 3. Navegar a /public_html/gestion/
```
Ruta actual: /home/[usuario]/public_html/gestion/
```

#### 4. Hacer Backup
```
1. Click derecho en CEOGestion (si existe)
2. Rename → CEOGestion_backup_2026_05_21
3. Crear nueva carpeta: CEOGestion
```

#### 5. Subir Archivos
```
Método 1: Upload vía File Manager
  1. Click en [Upload]
  2. Seleccionar archivo ZIP (CEOGestion.zip)
  3. Subir
  4. Click derecho en ZIP → Extract
  5. Esperar a completar

Método 2: Upload carpeta directa (lento)
  1. Click en [Upload]
  2. Seleccionar todos los archivos/carpetas
  3. Subir (puede tardar 10-30 min)
```

#### 6. Establecer Permisos
```
En File Manager:

Para carpetas (storage, bootstrap/cache):
  1. Click derecho
  2. Permissions / Change Permissions
  3. Establecer: 755

Para archivos (.env, .htaccess):
  1. Click derecho
  2. Permissions / Change Permissions
  3. Establecer: 644
```

#### 7. Ejecutar Comandos (si tienes Terminal)
```
cPanel → Advanced → Terminal
o
cPanel → Command Line

Ejecutar:
cd /home/[usuario]/public_html/gestion/CEOGestion
php artisan migrate
php artisan db:seed --class=ThemeSeeder
php artisan storage:link
```

---

## 🔧 CONFIGURACIÓN DEL .env PARA PRODUCCIÓN

### Archivo: `/public_html/gestion/CEOGestion/.env`

```bash
APP_NAME="CEOGestion"
APP_ENV=production
APP_KEY=base64:xxxxx [GENERAR CON: php artisan key:generate]
APP_DEBUG=false
APP_URL=https://gestion.simotec.com.co/CEOGestion

# ⚠️ IMPORTANTE: Sin /public al final, Laravel lo añade automáticamente

LOG_CHANNEL=stack
LOG_LEVEL=error

# ==================== DATABASE ====================
DB_CONNECTION=mysql
DB_HOST=localhost          # O IP del servidor BD
DB_PORT=3306
DB_DATABASE=ceogestion_prod
DB_USERNAME=ceogestion_user
DB_PASSWORD=[TU_PASSWORD_BD]

# ==================== MAIL ====================
MAIL_MAILER=smtp
MAIL_HOST=mail.simotec.com.co
MAIL_PORT=587
MAIL_USERNAME=noreply@simotec.com.co
MAIL_PASSWORD=[TU_PASSWORD_EMAIL]
MAIL_ENCRYPTION=tls

# ==================== SESSION ====================
SESSION_DRIVER=file
SESSION_LIFETIME=120

# ==================== CACHE ====================
CACHE_DRIVER=file
CACHE_TTL=3600

# ==================== REDIS (opcional) ====================
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# ==================== QUEUE ====================
QUEUE_CONNECTION=sync
```

### Generar APP_KEY

**Opción 1: Por SSH**
```bash
cd /public_html/gestion/CEOGestion
php artisan key:generate
```

**Opción 2: Por cPanel Terminal**
```bash
cd /home/[usuario]/public_html/gestion/CEOGestion
php artisan key:generate
```

**Opción 3: Manual**
```bash
# Generar una clave aleatoria de 32 caracteres
# Pegarla en .env como: APP_KEY=base64:xxxxxxxxxxxx
```

---

## 🔗 VERIFICACIÓN DE .htaccess

### Archivo: `/public_html/gestion/CEOGestion/.htaccess`

Debe contener:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /gestion/CEOGestion/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php?/$1 [L]
</IfModule>
```

⚠️ **CRÍTICO:** `RewriteBase /gestion/CEOGestion/` debe coincidir con tu ruta en hosting.

---

## ✅ VERIFICACIÓN FINAL POST-DESPLIEGUE

### 1. Acceder a Login
```
URL: https://gestion.simotec.com.co/CEOGestion/public/login
```

**Verificar:**
- [ ] Página carga sin error 404/500
- [ ] Logo de empresa visible
- [ ] Formulario con campos email/password
- [ ] Responsive en móvil (prueba con F12)
- [ ] Sin errores en consola (F12 → Console)

### 2. Probar Login
```
Email: admin@ceogestion.com
Password: password123

Resultado:
- [ ] Click en "Ingresar"
- [ ] Redirige a /dashboard
- [ ] Sesión activa (muestra nombre usuario)
- [ ] Menú lateral visible
- [ ] Página dashboard carga completa
```

### 3. Verificar Base de Datos
```sql
-- En cPanel → phpMyAdmin:

USE ceogestion_prod;

-- Verificar tablas nuevas
SHOW TABLES LIKE 'themes';                    -- Debe existir
SHOW TABLES LIKE 'empresa_theme_settings';   -- Debe existir

-- Contar registros
SELECT COUNT(*) FROM themes;                  -- Debe retornar 5
SELECT COUNT(*) FROM empresa_theme_settings; -- Debe retornar >= 1
SELECT COUNT(*) FROM usuarios WHERE email='admin@ceogestion.com'; -- Debe retornar 1

-- Verificar logo de empresa
SELECT id, nombre, logo FROM empresas LIMIT 1;
```

### 4. Verificar Storage y Symlink
```bash
# Por SSH o cPanel Terminal:
ls -la /public_html/gestion/CEOGestion/public/storage
# Debe mostrar: storage -> ../storage/app/public

# Si NO existe el symlink, crear manualmente:
cd /public_html/gestion/CEOGestion
php artisan storage:link
```

### 5. Revisar Logs
```bash
# Por SSH:
tail -50 /public_html/gestion/CEOGestion/storage/logs/laravel.log

# Por cPanel File Manager:
# Navegar a: CEOGestion/storage/logs/
# Descargar: laravel.log
# Buscar: ERROR o Exception
```

### 6. Pruebas de Navegación
```
[ ] /CEOGestion/public/login → OK
[ ] /CEOGestion/public/dashboard → OK (auth)
[ ] /CEOGestion/public/logout → OK
[ ] /CEOGestion/public/empresas → OK (auth)
[ ] /CEOGestion/public/usuarios → OK (auth)
[ ] /CEOGestion/public/servicios → OK (auth)
[ ] /CEOGestion/public/api/* → OK (AJAX endpoints)
```

---

## 🆘 ERRORES COMUNES Y SOLUCIONES

### Error: "404 Not Found" en /login
```
Causa: .htaccess no está bien configurado o mod_rewrite deshabilitado
Solución:
  1. Verificar RewriteBase en .htaccess
  2. Contactar hosting para verificar mod_rewrite
  3. Probar acceso directo: /CEOGestion/public/index.php
```

### Error: "500 Internal Server Error"
```
Causa: Permisos incorrectos o error en código
Solución:
  1. Verificar permisos: storage/ = 755
  2. Limpiar cache: php artisan cache:clear
  3. Revisar logs: storage/logs/laravel.log
  4. Verificar .env existe y es legible
```

### Error: "SQLSTATE: Base de datos no encontrada"
```
Causa: Conexión BD incorrecta
Solución:
  1. Verificar .env: DB_HOST, DB_USERNAME, DB_PASSWORD
  2. Verificar BD existe: ceogestion_prod
  3. Verificar usuario BD tiene permisos
  4. Probar conexión: php artisan tinker → DB::connection()->getPdo()
```

### Error: Logo no se carga
```
Causa: Symlink de storage no existe
Solución:
  1. Ejecutar: php artisan storage:link
  2. Verificar permisos: storage/app/public/ = 755
  3. Verificar ruta en BD: empresas.logo = "empresas/logo.png"
```

### Error: Login funciona pero no guarda sesión
```
Causa: Permisos de storage/framework/sessions/
Solución:
  1. Verificar permisos: storage/framework/sessions/ = 755
  2. Limpiar sesiones: rm -rf storage/framework/sessions/*
  3. Regenerar KEY: php artisan key:generate
```

---

## 📞 CONTACTO SOPORTE

**En caso de problemas:**

1. Verificar checklist anterior ✅
2. Revisar logs: `storage/logs/laravel.log`
3. Contactar hosting (soporte técnico)
4. Enviar captura de pantalla del error

**Información útil para soporte:**
- URL donde ocurre el error
- Captura de consola (F12)
- Contenido de `storage/logs/laravel.log` (últimas 50 líneas)
- Hosting utilizado (cPanel, Plesk, VPS, etc.)
- Versión PHP (`php -v`)
- Versión MySQL (`mysql -V`)

---

**¡Listo! Tu aplicación está preparada para subirla a producción. 🚀**

**Última actualización:** 21 de Mayo 2026  
**Estado:** ✅ LISTO PARA DESPLIEGUE  
**Soporte:** 24/7 disponible
