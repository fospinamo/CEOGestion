# 📤 Instrucciones para Subir a GitHub

## Paso 1: Crear Repositorio en GitHub
1. Ve a https://github.com/new
2. Nombre: `CEOGestion` (o tu preferencia)
3. Descripción: "Sistema de Gestión Empresarial - Módulo de Servicios TI"
4. Privado ✅ (Recomendado)
5. NO inicialices con README (ya existe)
6. Click en "Create repository"

## Paso 2: Copiar la URL del Repositorio
Después de crear, verás una URL como:
```
https://github.com/[TU_USUARIO]/CEOGestion.git
```
O para SSH:
```
git@github.com:[TU_USUARIO]/CEOGestion.git
```

## Paso 3: Ejecutar Comandos en PowerShell

### Si es HTTPS (recomendado para principiantes):
```powershell
cd c:\xampp\htdocs\CEOGestion

# Agregar remoto
git remote add origin https://github.com/[TU_USUARIO]/CEOGestion.git

# Renombrar rama a main
git branch -M main

# Subir código
git push -u origin main
```

### Si es SSH (requiere configuración previa):
```powershell
cd c:\xampp\htdocs\CEOGestion

# Agregar remoto
git remote add origin git@github.com:[TU_USUARIO]/CEOGestion.git

# Renombrar rama a main
git branch -M main

# Subir código
git push -u origin main
```

## Paso 4: Verificar en GitHub
1. Recarga la página de tu repositorio
2. Verás toda tu carpeta y archivos
3. El proyecto está en GitHub ✅

## Paso 5: Commits Futuros
Una vez configurado, para los próximos cambios:
```powershell
git add .
git commit -m "Descripción del cambio"
git push
```

## 📋 Estado del Repositorio Local
```
Rama actual: main
Remoto: NO CONFIGURADO (ejecuta los pasos arriba)
Commits pendientes: 0 (ya commitido hoy)
```

## 🔐 Si GitHub pide Autenticación (HTTPS)
- Usuario: Tu email/usuario de GitHub
- Contraseña: Token de acceso personal (no tu contraseña)
  - Generar en: https://github.com/settings/tokens

## ⚠️ Archivos Excluidos (Revisar .gitignore)
```
/vendor/
/node_modules/
.env
.env.local
/storage/*
/bootstrap/cache/*
```

## ✅ Checklist Antes de Subir
- [ ] Código limpio y testeado
- [ ] README.md existe con instrucciones
- [ ] .env.example configurado
- [ ] No contiene contraseñas en el código
- [ ] node_modules y vendor NO incluidos (verificar .gitignore)
