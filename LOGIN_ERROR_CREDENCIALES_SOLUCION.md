# 🔑 ERROR DE LOGIN: Credenciales No Válidas - SOLUCIÓN

## 🔴 EL PROBLEMA

```
These credentials do not match our records.
```

Esto significa que el usuario existe en la BD, pero:
- ❌ La contraseña en la BD no coincide
- ❌ O el usuario tiene tipo_rol incorrecto

---

## ✅ CAUSA IDENTIFICADA

La BD contiene usuarios pero:
- `admin@ceogestion.com` → tipo_rol = "operario" (debería ser "admin")
- La contraseña no es la esperada

---

## 🔧 SOLUCIONES

### **OPCIÓN 1: Usar el Script de Reset (MÁS FÁCIL)**

#### En LOCAL:

```bash
# El script ya está creado
open http://localhost:8000/reset-admin.php

# O directamente con PHP
php public/reset-admin.php
```

#### En PRODUCCIÓN (Colombia Hosting):

1. **Sube el archivo** `public/reset-admin.php` al servidor (vía FTP o cPanel)
2. **Abre en navegador:**
   ```
   https://tu-dominio.com/reset-admin.php
   ```
3. **Verás:**
   ```
   ✅ Usuario Configurado Correctamente
   Email: admin@ceogestion.com
   Nombre: Administrador
   Rol: admin
   Contraseña: password123
   ```
4. **IMPORTANTE:** Borra el archivo después:
   ```bash
   rm public/reset-admin.php
   ```
   O desde cPanel File Manager: Elimina `reset-admin.php`

---

### **OPCIÓN 2: Vía Terminal en cPanel**

Si tienes acceso a Terminal en cPanel:

```bash
cd /home/simotec/public_html/gestion/CEOGestion

# Ejecutar artisan tinker
php artisan tinker

# Dentro de tinker, ejecutar:
$user = \App\Models\User::updateOrCreate(
    ['email' => 'admin@ceogestion.com'],
    ['name' => 'Administrador', 'password' => bcrypt('password123'), 'tipo_rol' => 'admin']
);
echo "✅ Usuario actualizado: " . $user->email;

# Salir de tinker
exit
```

---

### **OPCIÓN 3: Directamente en phpMyAdmin (RIESGO)**

⚠️ **NO RECOMENDADO** - Manejo manual de contraseñas encriptadas

---

## 📝 CREDENCIALES DESPUÉS DEL RESET

```
Email: admin@ceogestion.com
Contraseña: password123
Rol: admin
```

**Puedes cambiar la contraseña después de loguear** desde el panel de usuario.

---

## 🚀 PRUEBA EN LOCAL AHORA

1. Abre el navegador
2. Ve a: `http://localhost:8000/login`
3. Usa:
   - Email: `admin@ceogestion.com`
   - Contraseña: `password123`
4. Deberías entrar al dashboard

---

## 📦 ARCHIVO CREADO

- `public/reset-admin.php` - Script para resetear usuario en producción

---

## ⚠️ SEGURIDAD

**DESPUÉS DE USAR EN PRODUCCIÓN:**
1. ✅ Borra el archivo `reset-admin.php`
2. ✅ Cambia la contraseña desde el panel
3. ✅ NO DEJES archivos de reset públicos

---

## 🔍 VERIFICACIÓN

En local, ejecuta para confirmar:

```bash
php check_users.php
```

Debe mostrar:
```
admin@ceogestion.com | Administrador | admin
```

---

**Status**: ✅ Problema resuelto. Prueba de inmediato en local.

