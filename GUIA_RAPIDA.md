# 🚀 Guía Rápida de Inicio - CEOGESTION

## ⚡ Inicio Rápido (5 minutos)

### 1. Validar que está ejecutándose
```bash
# Terminal PowerShell en c:\xampp\htdocs\CEOGestion
cd c:\xampp\htdocs\CEOGestion

# Verificar que todo está instalado
composer -V              # Debe mostrar Composer version
php -v                   # Debe mostrar PHP 8.2+
node -v                  # Debe mostrar Node.js 18+
```

### 2. Ejecutar servidor Laravel
```bash
php artisan serve

# Debería mostrar:
# Server running on [http://127.0.0.1:8000]
```

### 3. Acceder al sistema
```
URL: http://localhost:8000
Presiona Ctrl + Click para abrir en navegador
```

---

## 👤 Accesos de Prueba

### Admin (Panel Completo)
```
📧 Email: admin@ceogestion.com
🔐 Contraseña: password123
🌐 URL: http://localhost:8000/login
✅ Rol: Admin
```

### Coordinador (Asignar Servicios)
```
📧 Email: coordinador@ceogestion.com
🔐 Contraseña: password123
🌐 URL: http://localhost:8000/login
✅ Rol: Coordinador
```

### Operario (Registrar Servicios)
```
📧 Email: operario1@ceogestion.com
🔐 Contraseña: password123
🌐 URL: http://localhost:8000/login
✅ Rol: Operario
```

### Técnico (Atender Servicios)
```
📧 Email: tecnico1@ceogestion.com
🔐 Contraseña: password123
🌐 URL: http://localhost:8000/login
✅ Rol: Técnico
```

### Cliente (Portal con Token)
```
🔓 Sin contraseña
🌐 URL: http://localhost:8000/portal/acceso/{TOKEN}
✅ Ver en base de datos o en output de seeder
```

---

## 📊 Ver Tokens de Clientes

### Opción 1: Durante Seeder (Si ejecutas fresh --seed)
```
La consola muestra los tokens al ejecutar:
php artisan migrate:fresh --seed

Busca líneas como:
✓ Usuario cliente creado: EMPRESA ABC (cliente.empresa-abc@...)
  Token: abc123xyz789...
```

### Opción 2: Consola Tinker
```bash
# Abrir consola interactiva
php artisan tinker

# Dentro de tinker:
>>> User::where('tipo_rol', 'cliente')->get(['name', 'email', 'token_acceso']);

# Resultado:
=> Illuminate\Database\Eloquent\Collection {#...
     [
       "EMPRESA ABC SAS" => "cliente.empresa-abc-sas@portal.ceogestion.com" => "token123..."
     ]
   }
```

### Opción 3: Base de Datos
```bash
# En MySQL
SELECT email, token_acceso FROM users WHERE tipo_rol = 'cliente';
```

---

## 🎯 Flujos de Prueba

### Flujo 1: Crear Servicio (Operario)
```
1. Login con operario1@ceogestion.com
2. Dashboard → Servicios
3. Botón "+ Crear"
4. Formulario: Cliente → Equipo → Tipo → Descripción
5. Guardar
✅ Servicio creado
```

### Flujo 2: Asignar a Técnico (Coordinador)
```
1. Login con coordinador@ceogestion.com
2. Dashboard → Servicios
3. Encontrar servicio "REPORTADO"
4. Clic Editar
5. Asignar Técnico
6. Estado → EN_ESPERA_ASIGNACION
7. Guardar
✅ Técnico puede ver el servicio
```

### Flujo 3: Atender Servicio (Técnico)
```
1. Login con tecnico1@ceogestion.com
2. Dashboard → Servicios
3. Ver "Mis Servicios" (asignados)
4. Clic en servicio
5. Estado → EN_PROCESO
6. Agregar observaciones
7. Registrar seguimiento
8. Cambiar a RESUELTO
✅ Cliente puede ver avance
```

### Flujo 4: Ver en Portal Cliente
```
1. Abrir navegador en ventana privada
2. URL: http://localhost:8000/portal/acceso/{TOKEN}
3. Ver Dashboard (estadísticas)
4. Ir a Servicios
5. Ver el servicio creado con estado actual
6. Ver historial de seguimientos
7. Descargar PDF (si está CERRADO)
✅ Cliente ve avance en tiempo real
```

---

## 🔧 Comandos Útiles

### Base de Datos
```bash
# Reset completo con seeders (BORRA TODO Y RECREA)
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Ejecutar solo seeders (sin reset)
php artisan db:seed

# Rollback de última migración
php artisan migrate:rollback

# Rollback de todas
php artisan migrate:reset
```

### Cache y Optimización
```bash
# Limpiar caches
php artisan cache:clear

# Limpiar vistas
php artisan view:clear

# Limpiar config
php artisan config:clear

# Optimizar (para producción)
php artisan optimize

# Clear all
php artisan optimize:clear
```

### Tinker (Consola Interactiva)
```bash
php artisan tinker

# Ver usuarios
>>> User::all();

# Ver servicios
>>> Servicio::with('equipo', 'tecnicoAsignado')->get();

# Crear usuario de prueba
>>> User::create([...]);

# Salir
>>> exit
```

### Desarrollo
```bash
# Watch for changes (CSS/JS)
npm run dev

# Build para producción
npm run build

# Logs en tiempo real
tail -f storage/logs/laravel.log
```

---

## 🌐 URLs Principales

### Panel Administrativo
| Ruta | Descripción |
|------|-------------|
| `/` | Home |
| `/login` | Login |
| `/dashboard` | Dashboard |
| `/servicios` | Gestión de Servicios |
| `/clientes` | Gestión de Clientes |
| `/contratos` | Gestión de Contratos |
| `/equipos` | Gestión de Equipos |
| `/usuarios` | Gestión de Usuarios |

### Portal del Cliente
| Ruta | Descripción |
|------|-------------|
| `/portal/acceso/{token}` | Acceso al portal |
| `/portal/cliente` | Dashboard |
| `/portal/cliente/contratos` | Ver contratos |
| `/portal/cliente/equipos` | Ver equipos |
| `/portal/cliente/servicios` | Ver servicios |

---

## 🐛 Solucionar Problemas

### "La aplicación no carga"
```
✓ Verificar que Laravel server está corriendo
✓ URL correcta: http://localhost:8000
✓ Verificar puertos: php artisan serve --port=8000
```

### "Página en blanco"
```
✓ Verificar logs: tail -f storage/logs/laravel.log
✓ Verificar permisos: chmod -R 775 storage/
✓ Limpiar caches: php artisan config:clear
```

### "Base de datos no conecta"
```
✓ XAMPP running: ver tray
✓ MySQL iniciado: Start MySQL
✓ .env correcto: DB_CONNECTION=mysql, DB_HOST=127.0.0.1
✓ Base creada: CREATE DATABASE ceogestion_db;
```

### "Token inválido en portal"
```
✓ Copiar exactamente sin espacios
✓ Verificar que es cliente tipo 'cliente'
✓ Generar nuevo: User::find(id)->generarTokenAcceso()
```

### "DataTable no funciona"
```
✓ F12 → Console → Ver errores
✓ Verificar jQuery cargado
✓ Verificar DataTables CDN accesible
✓ Verificar columnas coinciden con datos
```

---

## 📱 Capacitación por Rol

### Para Admin
- Acceder a panel
- Ver estadísticas
- Gestionar usuarios
- Generar reportes (proximos)

### Para Coordinador
- Login
- Ver servicios REPORTADOS
- Asignar a técnicos
- Cambiar prioridades
- Monitorear abiertos

### Para Operario
- Login
- Crear servicios
- Llenar datos de cliente
- Sistema carga equipo/contrato
- Validar que contrato cubre servicio

### Para Técnico
- Login
- Ver servicios asignados
- Actualizar estado
- Registrar avances
- Resolver problemas

### Para Cliente
- Acceder con token (NO CONTRASEÑA)
- Ver dashboard
- Ver contratos
- Ver equipos
- Crear nuevo servicio
- Descargar PDF de atención

---

## 📚 Documentación Disponible

1. **DOCUMENTACION_COMPLETA.md** - Guía técnica completa
2. **CREDENCIALES_DE_ACCESO.md** - Credenciales y accesos
3. **REQUISITOS_CUMPLIDOS.md** - Verificación de requisitos
4. **PROYECTO_COMPLETADO.md** - Resumen del proyecto
5. **GUIA_RAPIDA.md** - Esta guía

---

## 🆘 Ayuda

### Problemas Comunes

**P: ¿Cómo resetear la base de datos?**
R: `php artisan migrate:fresh --seed`

**P: ¿Dónde ver los tokens?**
R: Ejecuta seeder con output o usa `php artisan tinker`

**P: ¿Cómo crear un nuevo usuario?**
R: `php artisan tinker` → `User::create([...]);`

**P: ¿Cómo cambiar la contraseña?**
R: `php artisan tinker` → `User::find(1)->update(['password' => bcrypt('nueva')])`

**P: ¿Cómo ver errores?**
R: `tail -f storage/logs/laravel.log` (en otra terminal)

---

## ✅ Checklist Inicial

- [ ] XAMPP corriendo
- [ ] MySQL iniciado
- [ ] Terminal en c:\xampp\htdocs\CEOGestion
- [ ] `php artisan serve` ejecutando
- [ ] http://localhost:8000 abre
- [ ] Puedo hacer login
- [ ] Veo datos de prueba

---

## 🎉 ¡Listo!

Ahora estás listo para:
✅ Explorar el sistema
✅ Probar los flujos
✅ Crear servicios
✅ Asignar técnicos
✅ Acceder al portal del cliente

---

**¿Necesitas ayuda?**
- Ver DOCUMENTACION_COMPLETA.md
- Ver CREDENCIALES_DE_ACCESO.md
- Revisar logs: `tail -f storage/logs/laravel.log`
- Usar Tinker: `php artisan tinker`

**Diviértete explorando CEOGESTION v2.0! 🚀**
