#!/bin/bash

# ============================================================================
# SCRIPT DE DESPLIEGUE AUTOMÁTICO - CEOGestion
# Ejecutar en producción para desplegar todos los cambios
# Uso: bash deploy.sh
# ============================================================================

set -e  # Exit on error

echo "=========================================="
echo "  🚀 DESPLIEGUE CEOGestion - Temas & Logo"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ Error: artisan file not found. Make sure you're in the Laravel root directory${NC}"
    exit 1
fi

echo -e "${YELLOW}1. Limpiando caches...${NC}"
php artisan cache:clear
php artisan view:clear
php artisan config:clear
echo -e "${GREEN}✓ Caches limpiados${NC}"
echo ""

echo -e "${YELLOW}2. Ejecutando migraciones...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migraciones completadas${NC}"
echo ""

echo -e "${YELLOW}3. Ejecutando seeders...${NC}"
php artisan db:seed --class=ThemeSeeder --force
echo -e "${GREEN}✓ Temas creados en la base de datos${NC}"
echo ""

echo -e "${YELLOW}4. Creando symlink de storage...${NC}"
# Remove existing link if it exists
if [ -L "public/storage" ]; then
    rm public/storage
    echo "  (Symlink anterior removido)"
fi

php artisan storage:link
echo -e "${GREEN}✓ Storage link creado${NC}"
echo ""

echo -e "${YELLOW}5. Optimizando aplicación...${NC}"
php artisan optimize
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✓ Optimizaciones completadas${NC}"
echo ""

echo -e "${YELLOW}6. Verificando base de datos...${NC}"
php artisan db:show > /dev/null 2>&1 && echo -e "${GREEN}✓ Conexión a BD correcta${NC}" || echo -e "${RED}✗ Error en conexión a BD${NC}"
echo ""

echo -e "${YELLOW}7. Resumen final...${NC}"
echo -e "  ${GREEN}✓ Caches limpiados${NC}"
echo -e "  ${GREEN}✓ Migraciones ejecutadas${NC}"
echo -e "  ${GREEN}✓ Themes seeded${NC}"
echo -e "  ${GREEN}✓ Storage link creado${NC}"
echo -e "  ${GREEN}✓ Aplicación optimizada${NC}"
echo ""

echo "=========================================="
echo -e "  ${GREEN}✅ DESPLIEGUE COMPLETADO${NC}"
echo "=========================================="
echo ""
echo "Próximos pasos:"
echo "1. Acceder a login: https://tudominio.com/login"
echo "2. Verificar que el logo de empresa sea visible"
echo "3. Probar login con admin@ceogestion.com"
echo ""
