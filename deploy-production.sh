#!/bin/bash

# ============================================
# Script de DESPLIEGUE en PRODUCCIÓN (cPanel)
# Ejecutar EN EL SERVIDOR después de subir código
# Uso: bash deploy-production.sh
# ============================================

APP_PATH="/home/tu-usuario/public_html"
echo "🚀 DESPLEGANDO EN PRODUCCIÓN..."
echo "Directorio: $APP_PATH"
echo ""

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Función para validar comando
check_command() {
    if ! command -v $1 &> /dev/null; then
        echo "${RED}❌ Comando no encontrado: $1${NC}"
        return 1
    fi
    return 0
}

# 1. Validar herramientas
echo "${YELLOW}1. Validando herramientas...${NC}"
check_command php
check_command mysql
check_command composer
echo "${GREEN}✓ Herramientas disponibles${NC}"
echo ""

# 2. Ir al directorio
cd $APP_PATH
echo "${YELLOW}2. Directorio actual: $(pwd)${NC}"
echo ""

# 3. Instalar/Actualizar dependencias
echo "${YELLOW}3. Instalando dependencias...${NC}"
if [ -f composer.json ]; then
    composer install --optimize-autoloader --no-dev
    echo "${GREEN}✓ Composer instalado${NC}"
else
    echo "${RED}❌ composer.json no encontrado${NC}"
    exit 1
fi
echo ""

# 4. Generar APP_KEY si no existe
echo "${YELLOW}4. Verificando APP_KEY...${NC}"
if grep -q "APP_KEY=$" .env; then
    echo "   Generando APP_KEY..."
    php artisan key:generate
    echo "${GREEN}✓ APP_KEY generado${NC}"
else
    echo "${GREEN}✓ APP_KEY ya existe${NC}"
fi
echo ""

# 5. Crear directorios necesarios
echo "${YELLOW}5. Creando directorios...${NC}"
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
echo "${GREEN}✓ Directorios creados${NC}"
echo ""

# 6. Configurar permisos
echo "${YELLOW}6. Configurando permisos...${NC}"
chmod -R 755 .
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
echo "${GREEN}✓ Permisos configurados${NC}"
echo ""

# 7. Limpiar caches anteriores
echo "${YELLOW}7. Limpiando caches antiguos...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "${GREEN}✓ Caches limpios${NC}"
echo ""

# 8. Compilar caches para producción
echo "${YELLOW}8. Compilando caches de producción...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "${GREEN}✓ Caches compilados${NC}"
echo ""

# 9. Verificar conexión BD
echo "${YELLOW}9. Verificando base de datos...${NC}"
php artisan tinker --execute "DB::connection()->getPdo(); echo 'BD conectada correctamente';"
if [ $? -ne 0 ]; then
    echo "${RED}❌ Error conexión BD. Verificar .env${NC}"
    exit 1
fi
echo "${GREEN}✓ Base de datos conectada${NC}"
echo ""

# 10. Migrar BD
read -p "¿Ejecutar migraciones de BD? (s/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Ss]$ ]]; then
    echo "${YELLOW}10. Migrando base de datos...${NC}"
    php artisan migrate --force
    echo "${GREEN}✓ Migraciones completadas${NC}"
else
    echo "Migraciones omitidas"
fi
echo ""

# 11. Seedear datos (si es primera vez)
read -p "¿Seedear datos iniciales? (s/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Ss]$ ]]; then
    echo "${YELLOW}11. Seedeando datos...${NC}"
    php artisan db:seed --force
    echo "${GREEN}✓ Datos seedeados${NC}"
fi
echo ""

# 12. Mostrar status
echo "${YELLOW}12. Estado final...${NC}"
echo ""
echo "📊 Rutas registradas:"
php artisan route:list | head -10
echo ""
echo "📊 Migraciones:"
php artisan migrate:status | head -15
echo ""

# 13. Verificaciones finales
echo "${YELLOW}13. Verificaciones finales...${NC}"
echo ""
echo "Archivos críticos:"
echo "  .env: $(test -f .env && echo '✓' || echo '✗')"
echo "  vendor/: $(test -d vendor && echo '✓' || echo '✗')"
echo "  storage/: $(test -d storage && echo '✓' || echo '✗')"
echo "  public/: $(test -d public && echo '✓' || echo '✗')"
echo ""

echo "${GREEN}✅ DESPLIEGUE COMPLETADO${NC}"
echo ""
echo "Accede a: https://tu-dominio.com"
echo "Usuario: admin@ceogestion.com"
echo "Contraseña: password123"
echo ""
echo "IMPORTANTE:"
echo "- Cambiar contraseña del admin"
echo "- Configurar variables de correo (.env)"
echo "- Revisar storage/logs/laravel.log si hay errores"
echo ""
