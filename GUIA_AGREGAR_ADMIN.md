# 👤 AGREGAR USUARIO ADMIN - GUÍA RÁPIDA

## 🎯 CREDENCIALES

```
Email: admin@ceogestion.com
Password: password123
Role: Admin (acceso total)
```

---

## 📋 PASOS:

### 1. En cPanel → phpMyAdmin

- Accede a: `https://2083-tu-dominio.com` (cPanel)
- Abre: **phpMyAdmin**
- Selecciona BD: `simotec_ceogestion_prod`

### 2. Ir a SQL

Click en pestaña: **SQL**

### 3. Copiar y Pegar

Copia esto y pégalo en phpMyAdmin:

```sql
INSERT INTO `users` (
    `name`,
    `email`,
    `email_verified_at`,
    `password`,
    `remember_token`,
    `created_at`,
    `updated_at`,
    `role_id`
) VALUES (
    'Administrador',
    'admin@ceogestion.com',
    NOW(),
    '$2y$12$SCbQqisiqY36qzVfU1Qon.LW1tZSg.d6uaz53pIFFNCIqTNqMuEPK',
    NULL,
    NOW(),
    NOW(),
    1
) ON DUPLICATE KEY UPDATE
    `password` = '$2y$12$SCbQqisiqY36qzVfU1Qon.LW1tZSg.d6uaz53pIFFNCIqTNqMuEPK',
    `updated_at` = NOW();
```

### 4. Ejecutar

Click: **Ejecutar** (o **Go**)

### 5. Verificar

Copia y ejecuta esto en SQL:

```sql
SELECT id, name, email, role_id FROM users WHERE email = 'admin@ceogestion.com';
```

Deberías ver:
```
| id | name          | email                   | role_id |
|----|---------------|-------------------------|---------|
| XX | Administrador | admin@ceogestion.com    | 1       |
```

---

## ✅ LISTO

Ahora puedes login con:
```
Email: admin@ceogestion.com
Password: password123
```

---

## ⚠️ NOTAS

- **role_id = 1** → Admin (acceso total)
- La contraseña está hasheada con bcrypt
- El comando usa `ON DUPLICATE KEY UPDATE` (si el usuario existe, actualiza la contraseña)
- El usuario estará verificado (`email_verified_at = NOW()`)

---

## 🔐 ¿Cambiar la contraseña?

Si necesitas otra contraseña diferente, dime y generaré el hash.

Por ahora, con `password123` es suficiente para acceder.
