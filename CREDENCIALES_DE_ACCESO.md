# 🔐 Credenciales de Acceso - CEOGESTION

## Usuarios del Sistema

### Admin
- **Email:** admin@ceogestion.com
- **Contraseña:** password123
- **Rol:** Administrador
- **Acceso:** http://localhost:8000/login

### Coordinador
- **Email:** coordinador@ceogestion.com
- **Contraseña:** password123
- **Rol:** Coordinador/Monitor de Servicios
- **Acceso:** http://localhost:8000/login

### Operarios
- **Email 1:** operario1@ceogestion.com (Turno mañana)
- **Email 2:** operario2@ceogestion.com (Turno tarde)
- **Contraseña:** password123
- **Rol:** Operario (Registra servicios)
- **Acceso:** http://localhost:8000/login

### Técnicos
- **Email 1:** tecnico1@ceogestion.com (Senior - Redes)
- **Email 2:** tecnico2@ceogestion.com (Servidores)
- **Email 3:** tecnico3@ceogestion.com (Soporte en sitio)
- **Contraseña:** password123
- **Rol:** Técnico (Atiende servicios)
- **Acceso:** http://localhost:8000/login

---

## Acceso Portal del Cliente

Los tokens se generan automáticamente en los seeders. Para obtenerlos:

```bash
# Ejecutar seeders (muestra los tokens)
php artisan migrate:fresh --seed

# O buscar en base de datos
php artisan tinker
>>> \App\Models\User::where('tipo_rol', 'cliente')->pluck('email', 'token_acceso');
```

### Ejemplo de Acceso

Después de ejecutar `migrate:fresh --seed`, verás en consola:

```
✓ Usuario cliente creado: EMPRESA ABC SAS (cliente.empresa-abc-sas@portal.ceogestion.com)
  Token: abc123xyz789...uj2h3j4
```

**Acceder al portal:**
```
http://localhost:8000/portal/acceso/abc123xyz789...uj2h3j4
```

---

## Flujo de Prueba Recomendado

### 1. Crear un Servicio (como Operario)
```
1. Login: operario1@ceogestion.com / password123
2. Ir a: /servicios/create
3. Seleccionar cliente (ej: EMPRESA ABC SAS)
4. Sistema carga equipos automáticamente
5. Seleccionar equipo
6. Llenar formulario
7. Guardar
```

### 2. Asignar a Técnico (como Coordinador)
```
1. Login: coordinador@ceogestion.com / password123
2. Ir a: /servicios
3. Buscar el servicio creado
4. Clic en Editar
5. Asignar técnico
6. Cambiar estado a "EN_PROCESO"
7. Guardar
```

### 3. Actualizar Servicio (como Técnico)
```
1. Login: tecnico1@ceogestion.com / password123
2. Ir a: /servicios
3. Buscar servicios asignados
4. Actualizar estado a "RESUELTO"
5. Agregar observaciones
6. Guardar
```

### 4. Ver en Portal del Cliente
```
1. Abrir: http://localhost:8000/portal/acceso/{TOKEN}
2. Dashboard muestra estadísticas
3. Ver en Servicios → Ver servicio creado
4. Descargar PDF de atención
5. Ver historial de seguimiento
```

---

## Base de Datos

### Conexión MySQL
- **Host:** 127.0.0.1
- **Puerto:** 3306
- **Base de datos:** ceogestion_db
- **Usuario:** root (XAMPP)
- **Contraseña:** (vacía en desarrollo)

### Ver Usuarios Creados
```bash
# Terminal
php artisan tinker

# En Tinker
>>> \App\Models\User::all()->map(fn($u) => [$u->email, $u->tipo_rol])->toArray()
```

### Ver Tokens de Clientes
```bash
# Terminal
php artisan tinker

# En Tinker
>>> \App\Models\User::where('tipo_rol', 'cliente')->get(['email', 'token_acceso']);
```

---

## Rutas Útiles

| Ruta | Descripción |
|------|------------|
| `/` | Home |
| `/login` | Login |
| `/dashboard` | Dashboard principal |
| `/servicios` | Gestión de servicios |
| `/clientes` | Gestión de clientes |
| `/contratos` | Gestión de contratos |
| `/equipos` | Gestión de equipos |
| `/usuarios` | Gestión de usuarios |
| `/portal/acceso/{token}` | Acceso portal cliente |
| `/portal/cliente` | Portal dashboard |

---

## Comandos Útiles

```bash
# Resetear base de datos
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Ejecutar seeders específicos
php artisan db:seed --class=UsuariosConRolesSeeder

# Limpiar caches
php artisan cache:clear && php artisan view:clear

# Generar optimizaciones
php artisan optimize

# Tinker (consola interactiva)
php artisan tinker
```

---

## Troubleshooting

### "Token inválido"
- El token debe ser copiado exactamente
- Cada cliente tiene un token único
- Los tokens se generan en migrate:fresh --seed

### "Sin servicios en portal"
- Los servicios deben estar creados para el cliente
- El servicio debe relacionarse con un equipo del cliente
- Ver que el cliente tiene sedes, áreas y equipos

### "No puedo crear servicio"
- El cliente debe tener contrato activo
- El contrato debe cubrir el tipo de servicio
- El cliente debe tener equipos registrados

### "DataTable no funciona"
- Verificar que no hay errores en console (F12)
- Asegurar que jQuery está cargado
- Verificar CDN de DataTables es accesible

---

**Creado:** 23 de Abril de 2026
**Sistema:** CEOGESTION v2.0
