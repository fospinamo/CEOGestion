#!/bin/bash

# ============================================
# Script de BACKUP automático de BD
# Ejecutar en Cron Job cada día: 0 2 * * * /home/tu-usuario/backup-db.sh
# ============================================

# Variables
APP_PATH="/home/tu-usuario/public_html"
BACKUP_DIR="/home/tu-usuario/backups"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="$BACKUP_DIR/ceogestion_$DATE.sql"

# Crear directorio de backups
mkdir -p $BACKUP_DIR

# Fuente: .env
cd $APP_PATH
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f 2)
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f 2)
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f 2)
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f 2)

# Hacer backup
echo "📦 Haciendo backup de $DB_NAME..."
mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "✅ Backup completado: $BACKUP_FILE"
    
    # Comprimir
    gzip "$BACKUP_FILE"
    echo "✅ Backup comprimido: $BACKUP_FILE.gz"
    
    # Eliminar backups con más de 30 días
    find $BACKUP_DIR -name "ceogestion_*.sql.gz" -mtime +30 -delete
    echo "🧹 Backups antiguos eliminados"
    
else
    echo "❌ Error en backup"
    exit 1
fi
