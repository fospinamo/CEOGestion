# 📘 CEOGESTION - Documentación Completa del Sistema

## 1. Descripción General

CEOGESTION es un sistema de gestión de servicios TI desarrollado con Laravel 11 que permite a las empresas gestionar:
- **Clientes corporativos** con múltiples sedes
- **Contratos de servicio** con SLA definidos
- **Equipos TI** organizados por ubicación
- **Servicios/Atenciones** con seguimiento en tiempo real
- **Portal del cliente** para acceso a servicios

---

## 2. Estructura de Datos

### 2.1 Jerarquía de Datos

```
Empresa (Proveedor de Servicios)
├── Usuarios (Admin, Técnico, Coordinador, Operario, Cliente)
├── Sedes (Ubicaciones de la empresa)
└── Clientes (Empresas que contratan servicios)
    ├── Sede (Ubicación del cliente en departamento/municipio)
    │   ├── Dirección propia
    │   ├── Contacto
    │   └── Áreas (Departamentos)
    │       └── Equipos (Servidores, PCs, etc)
    ├── Contratos (Acuerdos de servicio)
    │   ├── Tipo (Mantenimiento, Soporte, Etc)
    │   ├── SLA (Tiempo de respuesta/solución)
    │   └── Servicios Incluidos (Preventivo, Correctivo, etc)
    └── Servicios (Atenciones/Tickets)
        ├── Descripción del problema
        ├── Técnico asignado
        ├── Seguimiento (Historial de acciones)
        └── Documento de atención (PDF)
```

### 2.2 Modelos y Relaciones

| Modelo | Descripción | Relaciones |
|--------|-------------|-----------|
| `User` | Usuarios del sistema | belongsTo(Empresa), belongsTo(Sede), belongsTo(Cliente), hasMany(Servicio) |
| `Cliente` | Cliente que contrata servicios | belongsTo(Empresa), hasMany(Sede), hasMany(Contrato) |
| `Sede` | Ubicación del cliente | belongsTo(Cliente), belongsTo(Municipio), hasMany(Area) |
| `Area` | Departamento dentro de una sede | belongsTo(Sede), hasMany(Equipo) |
| `Equipo` | Hardware (servidor, PC, etc) | belongsTo(Area), belongsTo(TipoEquipo), hasMany(Servicio) |
| `Servicio` | Ticket/Atención TI | belongsTo(Equipo), belongsTo(Contrato), belongsTo(User), hasMany(SeguimientoServicio) |
| `Contrato` | Acuerdo de servicio | belongsTo(Cliente), hasMany(Servicio) |
| `ContratoServicio` | Mapeo de tipos de servicio en contrato | belongsTo(Contrato) |
| `SeguimientoServicio` | Historial de acciones en un servicio | belongsTo(Servicio), belongsTo(User) |

---

## 3. Tipos de Usuarios y Roles

### 3.1 Administrador (`admin`)
- **Acceso:** Sistema completo
- **Permisos:**
  - Ver estadísticas y reportes
  - Gestionar usuarios
  - Gestionar clientes
  - Ver cumplimiento de SLA
- **Email:** `admin@ceogestion.com`
- **Contraseña:** `password123`

### 3.2 Coordinador/Monitor (`coordinador`)
- **Acceso:** Gestión de servicios
- **Permisos:**
  - Ver servicios abiertos
  - Asignar servicios a técnicos
  - Cambiar prioridad
  - Ver carga de técnicos
- **Email:** `coordinador@ceogestion.com`
- **Contraseña:** `password123`

### 3.3 Operario (`operario`)
- **Acceso:** Registro de servicios
- **Permisos:**
  - Crear servicios cuando clientes llaman
  - Ver lista de clientes
  - Ver equipos del cliente
- **Emails:**
  - `operario1@ceogestion.com` (Turno mañana)
  - `operario2@ceogestion.com` (Turno tarde)
- **Contraseña:** `password123`

### 3.4 Técnico (`tecnico`)
- **Acceso:** Atención de servicios asignados
- **Permisos:**
  - Ver servicios asignados
  - Actualizar estado del servicio
  - Registrar seguimiento
  - Registrar tiempo de trabajo
- **Emails:**
  - `tecnico1@ceogestion.com` (Senior - Redes)
  - `tecnico2@ceogestion.com` (Servidores)
  - `tecnico3@ceogestion.com` (Soporte en sitio)
- **Contraseña:** `password123`

### 3.5 Cliente Corporativo (`cliente`)
- **Acceso:** Portal del cliente (URL con token)
- **Permisos:**
  - Ver propios contratos
  - Ver propios equipos
  - Ver propios servicios
  - Crear servicios
  - Descargar atenciones (PDF)
- **Acceso:** Via portal con token único

---

## 4. Portal del Cliente

### 4.1 Descripción
El portal del cliente permite a las empresas contratantes acceder a través de una URL única con token, sin necesidad de contraseña.

### 4.2 Funcionalidades

#### Dashboard
- Resumen de contratos activos
- Cantidad de equipos
- Servicios reportados (últimos 30 días)
- Estado de servicios (gráfico)

#### Contratos
- Listado de contratos activos
- SLA por tipo de servicio
- Tipos de servicio incluidos
- Valores del contrato

#### Equipos
- Listado de equipos operativos
- Código interno, tipo, ubicación, serial
- Área y sede
- DataTable con búsqueda y ordenamiento

#### Servicios
- Listado de todos los servicios reportados
- Botón para reportar nuevo servicio
- Modal de creación con validaciones
- Detalle de cada servicio
- Seguimiento en tiempo real
- Descarga de PDF de atención

### 4.3 Acceso al Portal

**URL de Acceso:**
```
http://localhost:8000/portal/acceso/{TOKEN_GENERADO}
```

**Tokens de Clientes (Generados automáticamente en seeders):**
Los tokens se muestran en la consola durante `php artisan migrate:fresh --seed`

Ejemplo de salida:
```
✓ Usuario cliente creado: EMPRESA XYZ SAS (cliente.empresa-xyz-sas@portal.ceogestion.com)
  Token: abc123def456ghi789...
```

### 4.4 Seguridad del Portal
- ✅ Token único de 64 caracteres
- ✅ Sesión por cliente (evita cross-access)
- ✅ Registro de accesos (IP, fecha/hora)
- ✅ Datos filtrados solo para el cliente autenticado
- ✅ Timeout de sesión configurable

---

## 5. Tipos de Servicio y SLA

### 5.1 Servicios Disponibles

| Tipo | Descripción | SLA Default |
|------|-------------|------------|
| **PREVENTIVO** | Mantenimiento programado | 24h respuesta / 72h solución |
| **CORRECTIVO** | Reparación de incidencias | 4h respuesta / 24h solución |
| **INSTALACION** | Instalación de equipos | 48h respuesta / 48h solución |
| **CONFIGURACION** | Ajustes y parametrizaciones | 24h respuesta / 48h solución |
| **CAPACITACION** | Entrenamiento de usuarios | 72h respuesta / N/A |
| **CONSULTA** | Asesoría técnica | 24h respuesta / N/A |

### 5.2 Prioridades de Servicio

| Prioridad | Descripción | Color |
|-----------|-------------|-------|
| **BAJA** | No afecta operación actual | Verde |
| **MEDIA** | Afecta funcionalidad parcial | Amarillo |
| **ALTA** | Afecta operación significativa | Naranja |
| **CRITICA** | Sistema completamente fuera de servicio | Rojo |

### 5.3 Estados de Servicio

| Estado | Descripción |
|--------|------------|
| **REPORTADO** | Servicio recién creado |
| **EN_ESPERA_ASIGNACION** | Esperando asignación a técnico |
| **EN_PROCESO** | Técnico está atendiendo |
| **RESUELTO** | Solución aplicada, en validación del cliente |
| **CERRADO** | Servicio completamente resuelto y documentado |

---

## 6. Flujos Principales

### 6.1 Registro de Servicio (Por Operario)

```
1. Operario login → /servicios/create
2. Selecciona cliente
3. Sistema carga equipos del cliente (AJAX)
4. Sistema carga contrato activo (AJAX)
5. Valida que contrato cubre tipo de servicio
6. Ingresa descripción del problema
7. Crea Servicio + SeguimientoServicio
8. Asigna SLA automáticamente
9. Notifica técnico/coordinador
```

### 6.2 Registro de Servicio (Por Cliente Portal)

```
1. Cliente accede con token → /portal/acceso/{TOKEN}
2. Sesión de portal se crea
3. Cliente → Portal → Servicios → Reportar Nuevo
4. Modal con formulario
5. Selecciona equipos disponibles
6. Validaciones del lado del cliente
7. Crea Servicio
8. Descarga automática de PDF de confirmación
9. Redirige a detalle del servicio
```

### 6.3 Asignación y Seguimiento de Servicio

```
1. Coordinador → /servicios
2. Ve servicio en estado "EN_ESPERA_ASIGNACION"
3. Asigna a técnico
4. Sistema crea SeguimientoServicio con acción "ASIGNACION"
5. Técnico ve en su dashboard servicios asignados
6. Técnico actualiza estado a "EN_PROCESO"
7. Técnico registra seguimiento periódicamente
8. Técnico cambia a "RESUELTO"
9. Cliente valida en portal
10. Coordinador cierra a "CERRADO"
```

---

## 7. API AJAX

### 7.1 Cargar Equipos por Cliente

**Ruta:** `GET /servicios/equipos/{cliente_id}`

**Respuesta:**
```json
[
  {
    "id": 1,
    "codigo_interno": "SRV-001",
    "nombre": "Servidor Principal"
  }
]
```

### 7.2 Cargar Contrato Activo

**Ruta:** `GET /servicios/contrato-activo/{cliente_id}`

**Respuesta:**
```json
{
  "id": 1,
  "numero_contrato": "CTR-001-2026",
  "sla_horas_respuesta": 24,
  "sla_horas_solucion": 72,
  "servicios": [
    {
      "tipo_servicio": "PREVENTIVO",
      "incluido": true,
      "sla_horas_respuesta": 24,
      "sla_horas_solucion": 72
    }
  ]
}
```

### 7.3 Asignar Técnico

**Ruta:** `POST /servicios/{id}/asignar-tecnico`

**Datos:**
```json
{
  "tecnico_id": 5
}
```

### 7.4 Cambiar Estado

**Ruta:** `POST /servicios/{id}/cambiar-estado`

**Datos:**
```json
{
  "estado": "EN_PROCESO",
  "observacion": "Se inició diagnóstico"
}
```

---

## 8. Vistas del Sistema

### 8.1 Panel Administrativo (Dashboard)
- `/dashboard` - Dashboard principal (requiere auth)

### 8.2 Gestión de Clientes
- `GET /clientes` - Listado con DataTable
- `GET /clientes/create` - Formulario creación
- `GET /clientes/{id}/edit` - Editar cliente

### 8.3 Gestión de Servicios
- `GET /servicios` - Listado con DataTable
- `GET /servicios/create` - Crear servicio
- `GET /servicios/{id}/edit` - Editar servicio
- `GET /servicios/{id}` - Detalle servicio

### 8.4 Portal del Cliente
- `GET /portal/acceso/{token}` - Entrada al portal
- `GET /portal/cliente` - Dashboard
- `GET /portal/cliente/contratos` - Ver contratos
- `GET /portal/cliente/equipos` - Ver equipos
- `GET /portal/cliente/servicios` - Ver servicios
- `POST /portal/cliente/servicios/crear` - Crear servicio
- `GET /portal/cliente/servicios/{id}/detalle` - Detalle
- `GET /portal/cliente/servicios/{id}/descargar` - PDF atención

---

## 9. Migraciones Base de Datos

### 9.1 Tablas Principales

| Tabla | Migraciones |
|-------|------------|
| **users** | 0001, 0003, 0023 |
| **empresas** | 0001, 0008 |
| **sedes** | 0002, 0009, 0013 |
| **clientes** | 0011 |
| **contratos** | 0012, 0020 |
| **contrato_servicios** | 0019 |
| **areas** | 0014 |
| **equipos** | 0015, 0022 |
| **servicios** | 0016, 0021 |
| **seguimientos_servicios** | 0018 |
| **documentos_adjuntos** | 0017 |

### 9.2 Ejecutar Migraciones

```bash
# Aplicar todas las migraciones
php artisan migrate

# Hacer rollback
php artisan migrate:rollback

# Reset y reseed
php artisan migrate:fresh --seed

# Ver estado
php artisan migrate:status
```

---

## 10. Seeders

### 10.1 Seeders Disponibles

```bash
# Ejecutar todos
php artisan migrate:fresh --seed

# Seeders individuales
php artisan db:seed --class=PaisSeeder
php artisan db:seed --class=ClienteSeeder
php artisan db:seed --class=UsuariosConRolesSeeder
```

### 10.2 Datos Generados

- **Paises:** Colombia
- **Departamentos:** Todos (32)
- **Municipios:** Principales
- **Barrios:** Bogotá
- **Tipos Equipos:** 8 tipos
- **Clientes:** 5 clientes de prueba
- **Contratos:** 5 contratos (1 por cliente)
- **Usuarios:** 1 Admin + 1 Coordinador + 2 Operarios + 3 Técnicos + 5 Clientes
- **Equipos:** 3 por cliente (15 total)
- **Servicios:** Ejemplos por cliente

---

## 11. Buenas Prácticas Implementadas

### 11.1 Código

✅ **Type Hints** - Todos los parámetros y retornos tienen tipos
✅ **PHPDoc** - Métodos documentados con descripción, parámetros, retorno
✅ **Soft Deletes** - Modelos no se borran, se marcan como eliminados
✅ **Eager Loading** - `with()` para evitar N+1 queries
✅ **Validación** - Validaciones en controladores
✅ **Relaciones** - Naming consistente en español
✅ **Seguridad** - Middleware de autenticación y autorización

### 11.2 Base de Datos

✅ **Foreign Keys** - Relaciones integrales
✅ **Índices** - En columnas frecuentes
✅ **Migraciones** - Versionadas y reversibles
✅ **Seeders** - Datos de prueba consistentes
✅ **Transacciones** - Para operaciones críticas

### 11.3 Seguridad

✅ **CSRF Protection** - En todos los formularios
✅ **Token Acceso** - Único por cliente (64 caracteres)
✅ **Sesiones Aisladas** - Por cliente en portal
✅ **Auditoría** - Registro de accesos
✅ **Soft Deletes** - Nunca pierde datos

### 11.4 UX/UI

✅ **DataTables** - Búsqueda, ordenamiento, paginación
✅ **Responsive** - Funciona en móvil
✅ **Badges** - Estados con colores intuitivos
✅ **Modales** - Formularios sin salir de página
✅ **AJAX** - Carga dinámica sin reload

---

## 12. Instalación y Setup

### 12.1 Requisitos

- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js 18+

### 12.2 Instalación

```bash
# 1. Clonar repositorio
git clone <repo> CEOGestion
cd CEOGestion

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar .env
cp .env.example .env
php artisan key:generate

# 4. Migraciones y seeders
php artisan migrate:fresh --seed

# 5. Build assets
npm run build

# 6. Servidor
php artisan serve
```

### 12.3 URLs de Acceso

- **Admin:** http://localhost:8000/login
- **Dashboard:** http://localhost:8000/dashboard
- **Portal Cliente:** http://localhost:8000/portal/acceso/{TOKEN}

---

## 13. Estructura de Carpetas

```
CEOGestion/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PortalClienteController.php  ✨ NUEVO
│   │   │   ├── ServicioController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       └── AuthToken.php  ✨ NUEVO
│   ├── Models/
│   │   ├── User.php  ✨ ACTUALIZADO
│   │   ├── Cliente.php
│   │   ├── Servicio.php
│   │   └── ...
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── 2026_04_22_000023_add_client_fields_to_users_table.php  ✨ NUEVO
│   │   └── ...
│   └── seeders/
│       ├── UsuariosConRolesSeeder.php  ✨ NUEVO
│       └── ...
├── resources/
│   ├── views/
│   │   ├── portal/
│   │   │   └── cliente/  ✨ NUEVO
│   │   │       ├── layout.blade.php
│   │   │       ├── dashboard.blade.php
│   │   │       ├── contratos.blade.php
│   │   │       ├── equipos.blade.php
│   │   │       ├── servicios.blade.php
│   │   │       └── servicios/
│   │   │           ├── detalle.blade.php
│   │   │           └── pdf-atencion.blade.php
│   │   └── ...
│   └── css/, js/
├── routes/
│   └── web.php  ✨ ACTUALIZADO
├── bootstrap/
│   └── app.php  ✨ ACTUALIZADO
└── ...
```

---

## 14. Próximas Mejoras (Roadmap)

- [ ] Sistema de notificaciones por email/SMS
- [ ] Dashboard coordinador con KPIs
- [ ] Reportes de SLA compliance
- [ ] Asignación automática de servicios
- [ ] Evaluación de técnicos por cliente
- [ ] Integración con sistemas de billing
- [ ] API REST pública para integraciones
- [ ] Aplicación móvil para técnicos
- [ ] Chat en tiempo real para soporte
- [ ] Backup automático de base de datos

---

## 15. Soporte y Contacto

**Empresa:** CEOGESTION SAS
**Email:** info@ceogestion.com
**Teléfono:** (1) 2345678
**Sitio Web:** www.ceogestion.com

---

**Última Actualización:** 23 Abril 2026
**Versión:** 2.0 (Con Portal del Cliente)
