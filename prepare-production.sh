#!/bin/bash

# ============================================
# Script de preparación para PRODUCCIÓN
# Uso: bash prepare-production.sh
# ============================================

echo "📦 PREPARANDO APLICACIÓN PARA PRODUCCIÓN..."
echo ""

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Verificar estado Git
echo "${YELLOW}1. Verificando estado Git...${NC}"
git status
if [[ $(git status -s) ]]; then
    echo "${RED}❌ Hay cambios sin commitear${NC}"
    echo "   Ejecuta: git add . && git commit -m 'mensaje'"
    exit 1
fi
echo "${GREEN}✓ Repositorio limpio${NC}"
echo ""

# 2. Instalar dependencias PHP
echo "${YELLOW}2. Instalando dependencias PHP...${NC}"
composer install --optimize-autoloader --no-dev
echo "${GREEN}✓ Dependencias instaladas${NC}"
echo ""

# 3. Instalar dependencias Node
echo "${YELLOW}3. Compilando assets...${NC}"
npm install
npm run build
echo "${GREEN}✓ Assets compilados${NC}"
echo ""

# 4. Limpiar caches
echo "${YELLOW}4. Limpiando caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "${GREEN}✓ Caches limpios${NC}"
echo ""

# 5. Verificar .env.production
echo "${YELLOW}5. Verificando configuración...${NC}"
if [ ! -f .env ]; then
    echo "${RED}❌ No existe .env${NC}"
    echo "   Copia: cp .env.example .env"
    exit 1
fi
echo "${GREEN}✓ .env existe${NC}"
echo ""

# 6. Resumen
echo "${GREEN}✅ APLICACIÓN LISTA PARA DESPLIEGUE${NC}"
echo ""
echo "Próximos pasos:"
echo "1. Revisar .env con variables de PRODUCCIÓN"
echo "2. Subir a servidor con: git push origin master"
echo "3. En servidor ejecutar: php artisan migrate --force"
echo "4. Verificar en: https://tu-dominio.com"
echo ""
