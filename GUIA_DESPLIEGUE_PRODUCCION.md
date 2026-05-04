# 📦 GUÍA DE DESPLIEGUE A PRODUCCIÓN - CEOGestion

## 🎯 Objetivo
Mover la aplicación Laravel de desarrollo a servidor de producción (cPanel)

---

## 📋 REQUISITOS PREVIOS

### En tu máquina local:
- [ ] Git instalado
- [ ] Código limpio y sin errores
- [ ] Base de datos local está estable
- [ ] Todos los cambios están commiteados

### En el servidor:
- [ ] cPanel habilitado
- [ ] Acceso SSH disponible
- [ ] Dominio apuntando al servidor
- [ ] PHP 8.2+ instalado
- [ ] MySQL/MariaDB disponible
- [ ] Composer instalado (o se instala vía SSH)

---

## ✅ PASO 1: PREPARAR CÓDIGO LOCALMENTE

### 1.1 Verificar que todo está limpio

```bash
# Asegurarse que no hay cambios sin commitear
git status

# Si hay cambios:
git add .
git commit -m "Último commit antes de despliegue"

# Ver últimos commits
git log --oneline -5
```

### 1.2 Crear rama de despliegue (RECOMENDADO)

```bash
# Crear rama para producción
git checkout -b produccion

# O simplemente asegúrate de estar en rama master:
git checkout master
```

### 1.3 Verificar que no hay archivos sensibles

Revisar que NO subas:
```
.env                    ← Configuración local, NO incluir
.env.local             ← Configuración local, NO incluir
/vendor                ← Se instala en servidor
/node_modules          ← Se instala en servidor
.git/                  ← Solo para desarrollo
debug_*.php            ← Archivos de debug
storage/logs/*         ← Logs del servidor
```

**Verificar .gitignore:**
```bash
cat .gitignore
```

Debe incluir:
```
.env
.env.local
.env.*.local
vendor/
node_modules/
storage/
bootstrap/cache/
```

### 1.4 Compilar Assets (JS/CSS)

```bash
# Instalar dependencias npm
npm install

# Compilar para producción
npm run build

# Verificar que se compiló
ls -la public/build/
```

---

## 📡 PASO 2: CONEXIÓN AL SERVIDOR cPanel

### 2.1 Abrir Terminal SSH

**Opción A: Desde Windows (PowerShell)**
```powershell
# Cambiar direccion@servidor.com por tu usuario SSH
ssh usuario@tu-dominio.com

# O si tienes IP:
ssh usuario@123.456.789.012
```

**Opción B: Desde cPanel Terminal (si no tienes SSH local)**
- Entra a cPanel → Advanced → Terminal
- Ejecuta los comandos ahí

### 2.2 Navegar al directorio web

```bash
# Usualmente en cPanel es public_html
cd /home/tu-usuario/public_html

# Ver qué hay
ls -la
```

---

## 🚀 PASO 3: CREAR ESTRUCTURA EN SERVIDOR

### 3.1 Eliminar lo anterior (si existe)

```bash
# ADVERTENCIA: Esto ELIMINA TODO. Hacer backup primero

# Opción 1: Borrar todo y empezar nuevo
cd /home/tu-usuario
rm -rf public_html/*

# Opción 2: Hacer backup antes
cp -r public_html public_html_backup_`date +%Y%m%d`
```

### 3.2 Clonar repositorio

```bash
# Si tienes repositorio Git (GitHub, GitLab, etc.)
cd /home/tu-usuario/public_html
git clone https://github.com/tu-usuario/tu-repo.git .

# Si no tienes Git, ir a PASO 3.3

# Verificar que se clonó
ls -la
```

**Si Git pide contraseña:**
```bash
# Usar token en lugar de contraseña
git clone https://tu-usuario:tu-token@github.com/tu-usuario/tu-repo.git .
```

### 3.3 Alternativa: Subir archivos por FTP/SFTP

Si NO tienes Git en el servidor:

1. **Opción A: Usar WinSCP (Windows)**
   - Descargar WinSCP
   - Crear sesión SFTP
   - Conectar
   - Descargar archivos locales a `/public_html/`

2. **Opción B: Comprimir y descargar**
   ```bash
   # En tu máquina local:
   cd c:\xampp\htdocs
   
   # Crear archivo ZIP sin node_modules y vendor
   $exclude = @('vendor', 'node_modules', '.git', '.env.local', 'storage/logs')
   Compress-Archive -Path CEOGestion -DestinationPath CEOGestion_v1.zip -Exclude $exclude
   
   # Subir CEOGestion_v1.zip al servidor vía FTP
   # En el servidor descomprimir:
   unzip CEOGestion_v1.zip
   ```

---

## 📦 PASO 4: INSTALAR DEPENDENCIAS

### 4.1 Instalar PHP dependencies (Composer)

```bash
# Ir al directorio de la app
cd /home/tu-usuario/public_html

# Si Composer no está instalado en el servidor:
curl -sS https://getcomposer.org/installer | php
php composer.phar install --optimize-autoloader --no-dev

# Si Composer YA ESTÁ instalado:
composer install --optimize-autoloader --no-dev
```

### 4.2 Instalar JavaScript dependencies (si usas assets compilados)

```bash
# Si NO usaste npm run build localmente:
npm install
npm run build

# Si YA compilaste localmente, solo verifica:
ls -la public/build/
```

---

## ⚙️ PASO 5: CONFIGURAR ARCHIVO .env

### 5.1 Crear .env en el servidor

```bash
# Copiar desde ejemplo
cp .env.example .env

# O crear nuevo:
nano .env
```

### 5.2 Configurar variables críticas

**Editar .env con valores de PRODUCCIÓN:**

```env
# ============ PRODUCCIÓN ============

APP_NAME=CEOGestion
APP_ENV=production
APP_KEY=  # ← Se genera en PASO 5.3
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# === BASE DE DATOS ===
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_usuario_ceogestion
DB_USERNAME=tu_usuario_db
DB_PASSWORD=tu_contraseña_bd

# === CACHE ===
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# === MAIL (Opcional) ===
MAIL_MAILER=smtp
MAIL_HOST=mail.tu-dominio.com
MAIL_PORT=587
MAIL_USERNAME=email@tu-dominio.com
MAIL_PASSWORD=tu_contraseña_correo
MAIL_ENCRYPTION=tls

# === LOGGING ===
LOG_CHANNEL=stack
LOG_LEVEL=error

# === SEGURIDAD ===
TRUSTED_PROXIES=*
SESSION_SECURE_COOKIES=true
```

### 5.3 Generar APP_KEY

```bash
# Laravel genera automáticamente la clave
php artisan key:generate

# Verificar que .env tiene APP_KEY
grep APP_KEY .env
```

---

## 🗄️ PASO 6: BASE DE DATOS

### 6.1 Crear base de datos en cPanel

**Opción A: Vía cPanel**
1. Entra a cPanel → MySQL Databases
2. Crear nueva BD
3. Crear usuario y asignar a BD
4. Anotar: nombre BD, usuario, contraseña

**Opción B: Vía SSH**
```bash
# Conectar a MySQL
mysql -u root -p

# Ejecutar comandos:
CREATE DATABASE ceogestion_prod;
CREATE USER 'ceogestion_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON ceogestion_prod.* TO 'ceogestion_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6.2 Migrar base de datos

```bash
# Ir al directorio de la app
cd /home/tu-usuario/public_html

# Correr migraciones
php artisan migrate --force

# Correr seeders (popular BD con datos iniciales)
php artisan db:seed --force

# Verificar que funcionó
php artisan migrate:status
```

---

## 📁 PASO 7: CONFIGURAR PERMISOS

```bash
# Ir a directorio de app
cd /home/tu-usuario/public_html

# Asignar permisos de lectura/escritura
chmod -R 755 .
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
chmod -R 777 public/

# Verificar propietario
chown -R tu-usuario:tu-usuario .
```

---

## 🌐 PASO 8: CONFIGURAR SERVIDOR WEB (cPanel/Apache)

### 8.1 Configurar public root

**En cPanel:**
1. Entra → Addon Domains
2. O DocumentRoot settings
3. Apuntar a `/home/tu-usuario/public_html/public`
   - NO a `/home/tu-usuario/public_html`

### 8.2 Crear .htaccess (si no existe)

**Archivo: `/home/tu-usuario/public_html/public/.htaccess`**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 8.3 SSL/HTTPS (AutoSSL en cPanel)

```bash
# En cPanel → SSL/TLS
# Habilitado por defecto en la mayoría de hosts

# Verificar en servidor:
ls -la /home/tu-usuario/ssl/
```

---

## 🔐 PASO 9: SEGURIDAD - OCULTAR ARCHIVOS SENSIBLES

### 9.1 Crear .htaccess en raíz

**Archivo: `/home/tu-usuario/public_html/.htaccess`**

```apache
# Denegar acceso a archivos sensibles
<FilesMatch "\.(env|example|md|lock)$">
    Deny from all
</FilesMatch>

# Denegar acceso a directorios
<DirectoryMatch "(^|/)(\.|artisan|composer.json|storage)">
    Deny from all
</DirectoryMatch>
```

### 9.2 Verificar configuración PHP

```bash
# En cPanel → Select PHP Version
# Asegúrate de tener PHP 8.2+

# Extensiones necesarias:
# ✓ cURL
# ✓ GD
# ✓ ZIP
# ✓ PDO MySQL
# ✓ JSON
```

---

## ✅ PASO 10: VERIFICACIONES FINALES

### 10.1 Limpiar caches de producción

```bash
cd /home/tu-usuario/public_html

# Limpiar todos los caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Recolectar basura
php artisan optimize
```

### 10.2 Verificar la aplicación

**En navegador:**
```
https://tu-dominio.com
```

Debería:
- ✅ Cargar sin errores
- ✅ Redirigir a login si no estás autenticado
- ✅ Permitir login con admin/password123
- ✅ Ver dashboard después de login

### 10.3 Revisar logs

```bash
# Ver últimos errores
tail -50 storage/logs/laravel.log

# Monitoreo en tiempo real
tail -f storage/logs/laravel.log
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "The storage path does not exist"

```bash
mkdir -p storage/app/public
chmod -R 777 storage/
```

### Error: "No application encryption key has been specified"

```bash
php artisan key:generate
```

### Error: "Class not found"

```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000]: General error: 1030"

```bash
# Base de datos no existe o permisos incorrectos
php artisan migrate:reset
php artisan migrate --force
php artisan db:seed --force
```

### Blank page (error 500)

```bash
# Ver errores
tail -100 storage/logs/laravel.log

# Verificar .env está correcto
cat .env | grep DB_

# Limpiar caches
php artisan cache:clear
php artisan config:clear
```

---

## 📊 PASO 11: CRON JOBS (si necesitas tareas programadas)

```bash
# En cPanel → Cron Jobs

# Agregar esta línea:
* * * * * cd /home/tu-usuario/public_html && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔄 PASO 12: ACTUALIZACIONES FUTURAS

### Para actualizar código en producción:

```bash
cd /home/tu-usuario/public_html

# Descargar cambios
git pull origin master

# Instalar nuevas dependencias
composer install --optimize-autoloader --no-dev

# Correr nuevas migraciones
php artisan migrate --force

# Limpiar caches
php artisan optimize:clear
php artisan cache:clear
```

---

## 📋 CHECKLIST FINAL

- [ ] Código está en GitHub/GitLab (backup)
- [ ] .env configurado correctamente
- [ ] Base de datos migrada
- [ ] Permisos de carpetas correctos (777 storage/)
- [ ] SSL/HTTPS habilitado
- [ ] Dominio apunta al servidor correcto
- [ ] Aplicación carga sin errores
- [ ] Login funciona
- [ ] Base de datos conecta correctamente
- [ ] Logs se están generando correctamente
- [ ] Backups de BD configurados (Cron job)

---

## 🎯 VERIFICACIÓN DE PRODUCCIÓN

### Comando para verificar todo:

```bash
cd /home/tu-usuario/public_html

# Verificar estructura
echo "✓ Archivos:" && ls -la | head -20
echo ""
echo "✓ .env existe:" && test -f .env && echo "SÍ" || echo "NO"
echo ""
echo "✓ Permisos storage:" && ls -ld storage/
echo ""
echo "✓ BD conecta:" && php artisan tinker --execute="echo 'BD OK'"
echo ""
echo "✓ Rutas registradas:" && php artisan route:list | head -10
echo ""
echo "✓ Logs:" && tail -5 storage/logs/laravel.log
```

---

## 📞 SOPORTE

Si hay problemas, proporciona:
1. URL de la aplicación
2. Error exacto del navegador
3. Output de: `tail -50 storage/logs/laravel.log`
4. Output de: `php artisan migrate:status`
5. Output de: `php artisan tinker --execute="DB::connection()->getPdo()"`

---

**ÚLTIMA ACTUALIZACIÓN**: 4/05/2026
**VERSIÓN**: 1.0 - Despliegue inicial
