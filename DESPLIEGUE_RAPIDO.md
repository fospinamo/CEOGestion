# 🚀 DESPLIEGUE A PRODUCCIÓN - VERSIÓN RÁPIDA

## 📋 RESUMEN DEL PROCESO

```
LOCAL (tu máquina)         →    SERVIDOR (cPanel)        →    PRODUCCIÓN (en línea)
  ↓                               ↓                              ↓
1. Preparar código         2. Clonar repositorio          3. Acceder a https://tu-dominio.com
   (npm run build)            (git clone)
2. Compilar assets         2. Instalar dependencias
3. Limpiar caches          3. Configurar .env
4. Commitear               4. Migrar BD
                           5. Configurar permisos
```

---

## ⚡ INICIO RÁPIDO (15 MINUTOS)

### FASE 1: Preparar local (5 min)

```bash
# En tu máquina (Windows PowerShell):
cd c:\xampp\htdocs\CEOGestion

# Compilar assets
npm run build

# Limpiar caches
php artisan optimize:clear
php artisan cache:clear

# Verificar que está todo bien
git status  # Debe estar limpio (nada pendiente)
```

### FASE 2: Conectar a servidor (2 min)

```bash
# Abrir SSH a cPanel
ssh tu-usuario@tu-dominio.com

# Verificar conexión
pwd  # Debe mostrar: /home/tu-usuario
```

### FASE 3: Desplegar (8 min)

```bash
# EN EL SERVIDOR:

# 1. Crear directorio
cd /home/tu-usuario/public_html
rm -rf *  # ⚠️ CUIDADO: borra todo anterior

# 2. Clonar código
git clone https://github.com/tu-usuario/tu-repo.git .

# 3. Ejecutar script de despliegue (RECOMENDADO)
bash deploy-production.sh

# ✅ LISTO - La aplicación está en línea
```

---

## 📝 CHECKLIST RÁPIDO

```
ANTES DE DESPLEGAR:
☐ Todos los cambios commiteados (git status limpio)
☐ Assets compilados (npm run build ejecutado)
☐ .env tiene datos de PRODUCCIÓN (no desarrollo)
☐ Base de datos está lista
☐ SSL/HTTPS habilitado en cPanel
☐ Dominio apunta al servidor correcto

DURANTE DESPLIEGUE:
☐ SSH conectado al servidor
☐ Código clonado en /home/usuario/public_html/
☐ Permisos configurados (chmod 777 storage/)
☐ Migraciones ejecutadas (php artisan migrate --force)
☐ Caches compilados (php artisan config:cache)

DESPUÉS DE DESPLEGAR:
☐ Puedo acceder a https://tu-dominio.com
☐ Login funciona (admin@ceogestion.com)
☐ Dashboard muestra datos
☐ Sin errores en logs (tail storage/logs/laravel.log)
```

---

## 🎯 ARCHIVOS IMPORTANTES

| Archivo | Propósito | Ubicación |
|---------|----------|-----------|
| `GUIA_DESPLIEGUE_PRODUCCION.md` | Guía detallada (12 pasos) | Raíz |
| `prepare-production.sh` | Script pre-despliegue LOCAL | Raíz |
| `deploy-production.sh` | Script despliegue EN SERVIDOR | Raíz |
| `.env.production.example` | Template .env producción | Raíz |
| `.htaccess` | Proteger archivos sensibles | Raíz |
| `backup-db.sh` | Backup automático BD | Raíz |

---

## 🔐 VARIABLES CRÍTICAS .env

**ANTES DE DESPLEGAR, EDITAR .env EN SERVIDOR CON:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_HOST=localhost
DB_DATABASE=ceogestion_prod
DB_USERNAME=usuario_bd
DB_PASSWORD=contraseña_segura
```

---

## 🐛 PROBLEMAS COMUNES

| Problema | Solución |
|----------|----------|
| "The storage path does not exist" | `mkdir -p storage && chmod 777 storage` |
| "No application encryption key" | `php artisan key:generate` |
| "SQLSTATE error" | Verificar .env (BD, usuario, contraseña) |
| "Class not found" | `composer dump-autoload` |
| Blank page / Error 500 | `tail storage/logs/laravel.log` |

---

## 📞 SOPORTE RÁPIDO

Si hay error, ejecuta:

```bash
# EN EL SERVIDOR:

# Ver logs
tail -50 storage/logs/laravel.log

# Verificar BD
php artisan tinker --execute "DB::connection()->getPdo(); echo 'BD OK';"

# Verificar rutas
php artisan route:list | head -20

# Limpiar caches
php artisan cache:clear && php artisan config:clear
```

---

## ✅ VALIDAR DESPUÉS DE DESPLEGAR

```bash
# En navegador:
https://tu-dominio.com

# Debe verse:
- Página login sin errores
- Botón "Iniciar Sesión" visible
- Estilos CSS cargados correctamente
- Sin mensajes de error

# Después de login:
- Dashboard carga
- Menú visible
- Datos de BD visible
```

---

## 🔄 ACTUALIZACIONES FUTURAS

Cuando hagas cambios y quieras actualizar producción:

```bash
# EN EL SERVIDOR:
cd /home/tu-usuario/public_html

# Descargar cambios
git pull origin master

# Instalar nuevas dependencias (si hay)
composer install --optimize-autoloader --no-dev

# Migraciones (si hay)
php artisan migrate --force

# Limpiar caches
php artisan optimize:clear
```

---

**¿Necesitas ayuda? Consulta `GUIA_DESPLIEGUE_PRODUCCION.md` para instrucciones detalladas.**
