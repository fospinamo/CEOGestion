#!/bin/bash
# Script para limpiar cache en producción - CEOGestion

echo "🧹 Iniciando limpieza de cache en producción..."
echo ""

# Cambiar a directorio del proyecto
cd /home/simotec/public_html/gestion/CEOGestion

echo "1️⃣ Limpiando vistas compiladas..."
php artisan view:clear
sleep 1

echo ""
echo "2️⃣ Limpiando cache de aplicación..."
php artisan cache:clear
sleep 1

echo ""
echo "3️⃣ Limpiando cache de configuración..."
php artisan config:clear
sleep 1

echo ""
echo "4️⃣ Limpiando cache de rutas..."
php artisan route:clear
sleep 1

echo ""
echo "5️⃣ Eliminando archivos compilados de vistas..."
rm -rf storage/framework/views/*
sleep 1

echo ""
echo "✅ CACHE COMPLETAMENTE LIMPIADO"
echo ""
echo "Ahora recarga la página en el navegador"
echo "http://tu-dominio.com/dashboard"
