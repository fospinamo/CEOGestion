# 📋 ESTRUCTURA MODULAR - CEOGestión

## Descripción de Módulos

La aplicación está organizada en **3 módulos principales** para una mejor organización y mantenimiento:

---

## 🏛️ MÓDULO ADMINISTRATIVO

**Rutas Base:** `/administrativo/`

Contiene las tablas básicas del sistema geográfico y administrativo.

### Componentes:

#### 1. **Países**
- **Controlador:** `app/Http/Controllers/Administrativo/PaisController.php`
- **Modelo:** `app/Models/Pais.php`
- **Vista:** `resources/views/administrativo/paises/`
- **Rutas:** `/administrativo/paises`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 2. **Departamentos**
- **Controlador:** `app/Http/Controllers/Administrativo/DepartamentoController.php`
- **Modelo:** `app/Models/Departamento.php`
- **Vista:** `resources/views/administrativo/departamentos/`
- **Rutas:** `/administrativo/departamentos`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 3. **Municipios**
- **Controlador:** `app/Http/Controllers/Administrativo/MunicipioController.php`
- **Modelo:** `app/Models/Municipio.php`
- **Vista:** `resources/views/administrativo/municipios/`
- **Rutas:** `/administrativo/municipios`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

---

## ⚙️ MÓDULO PARÁMETROS

**Rutas Base:** `/parametros/`

Contiene la configuración general del sistema y parámetros de operación.

### Componentes:

#### 1. **Empresa**
- **Controlador:** `app/Http/Controllers/Parametros/EmpresaController.php`
- **Modelo:** `app/Models/Empresa.php`
- **Vista:** `resources/views/parametros/empresas/`
- **Rutas:** `/parametros/empresas`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 2. **Sedes**
- **Controlador:** `app/Http/Controllers/Parametros/SedeController.php`
- **Modelo:** `app/Models/Sede.php`
- **Vista:** `resources/views/parametros/sedes/`
- **Rutas:** `/parametros/sedes`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 3. **Clientes**
- **Controlador:** `app/Http/Controllers/Parametros/ClienteController.php`
- **Modelo:** `app/Models/Cliente.php`
- **Vista:** `resources/views/parametros/clientes/`
- **Rutas:** `/parametros/clientes`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 4. **Áreas**
- **Controlador:** `app/Http/Controllers/Parametros/AreaController.php`
- **Modelo:** `app/Models/Area.php`
- **Vista:** `resources/views/parametros/areas/`
- **Rutas:** `/parametros/areas`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

#### 5. **Equipos**
- **Controlador:** `app/Http/Controllers/Parametros/EquipoController.php`
- **Modelo:** `app/Models/Equipo.php`
- **Vista:** `resources/views/parametros/equipos/`
- **Rutas:** `/parametros/equipos`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy
- **Acciones Especiales:** Export Excel, Export PDF

#### 6. **Tipos de Equipos**
- **Controlador:** `app/Http/Controllers/Parametros/TipoEquipoController.php`
- **Modelo:** `app/Models/TipoEquipo.php`
- **Vista:** `resources/views/parametros/tipos-equipos/`
- **Rutas:** `/parametros/tipos-equipos`
- **Acciones:** Index, Create, Store, Show, Edit, Update, Destroy

---

## 🔧 MÓDULO INCIDENCIAS

**Rutas Base:** `/incidencias/`

Contiene la gestión de servicios técnicos y atenciones al cliente.

### Componentes:

#### 1. **Servicios (Incidencias)**
- **Controlador:** `app/Http/Controllers/Incidencias/ServicioController.php`
- **Modelo:** `app/Models/Servicio.php`
- **Vista:** `resources/views/incidencias/servicios/`
- **Rutas Base:** `/incidencias/servicios`

**Acciones Estándar:**
- `GET /incidencias/servicios` - Listado de servicios
- `GET /incidencias/servicios/create` - Crear nuevo servicio
- `POST /incidencias/servicios` - Guardar nuevo servicio
- `GET /incidencias/servicios/{id}` - Ver detalle de servicio
- `GET /incidencias/servicios/{id}/edit` - Editar servicio
- `PUT /incidencias/servicios/{id}` - Actualizar servicio
- `DELETE /incidencias/servicios/{id}` - Eliminar servicio

**Acciones Especiales:**

##### Registrar Informe Técnico
- `GET /incidencias/servicios/{id}/informe` - Formulario informe
- `POST /incidencias/servicios/{id}/informe` - Guardar informe
- **Método:** `storeAttendance()`

##### Asignar Técnico
- `GET /incidencias/servicios/{id}/asignar` - Formulario asignación
- `POST /incidencias/servicios/{id}/asignar` - Guardar asignación
- **Método:** `storeAssign()`

##### Panel de Técnico
- `GET /incidencias/servicios/panel` - Ver servicios asignados
- **Método:** `panel()`

##### Generación de PDF
- `GET /incidencias/servicios/{id}/informe-pdf/descargar` - Descargar PDF
- `GET /incidencias/servicios/{id}/informe-pdf/ver` - Ver PDF en navegador
- **Métodos:** `downloadInformePDF()`, `viewInformePDF()`

---

## 📁 Estructura de Directorios

```
app/Http/Controllers/
├── Administrativo/
│   ├── PaisController.php
│   ├── DepartamentoController.php
│   └── MunicipioController.php
├── Parametros/
│   ├── EmpresaController.php
│   ├── SedeController.php
│   ├── ClienteController.php
│   ├── AreaController.php
│   ├── EquipoController.php
│   └── TipoEquipoController.php
└── Incidencias/
    └── ServicioController.php

resources/views/
├── administrativo/
│   ├── paises/
│   ├── departamentos/
│   └── municipios/
├── parametros/
│   ├── empresas/
│   ├── sedes/
│   ├── clientes/
│   ├── areas/
│   ├── equipos/
│   └── tipos-equipos/
└── incidencias/
    └── servicios/

routes/
├── web.php (principal)
├── administrativo.php (rutas del módulo)
├── parametros.php (rutas del módulo)
└── incidencias.php (rutas del módulo)
```

---

## 🔗 Integración en web.php

El archivo principal `routes/web.php` debe incluir:

```php
// Importar rutas modulares
require __DIR__ . '/administrativo.php';
require __DIR__ . '/parametros.php';
require __DIR__ . '/incidencias.php';
```

---

## 🔐 Middleware y Autenticación

Todos los módulos están protegidos con:
- `middleware('auth')` - Requiere usuario autenticado
- Nombres de rutas prefijados por módulo (ej: `administrativo.paises.index`)

---

## 📊 Flujo de Datos

### Crear un Servicio:
1. Acceder a `/incidencias/servicios/create`
2. Rellenar formulario con datos del cliente y equipo
3. Guardar servicio
4. Sistema asigna estado inicial

### Atender un Servicio:
1. Técnico ve panel `/incidencias/servicios/panel`
2. Abre servicio asignado
3. Completa informe técnico en `/incidencias/servicios/{id}/informe`
4. Captura firma del receptor
5. Genera PDF descargable

### Descargar Informe:
1. Desde `/incidencias/servicios/{id}` (vista detalle)
2. Botón "Descargar PDF"
3. Sistema genera PDF con:
   - Información del cliente
   - Datos del equipo
   - Descripción del servicio
   - Imágenes del trabajo realizado
   - Firma del receptor
   - Datos del técnico

---

## 🚀 Próximos Pasos

1. Crear controladores en cada módulo
2. Crear vistas para CRUD
3. Migrar lógica existente a nuevos controladores
4. Actualizar referencias de rutas en templates
5. Hacer pruebas de funcionamiento
6. Commit a GitHub

---

## 📝 Notas Importantes

- **Nombres de rutas:** Usan prefijos de módulo (ej: `parametros.equipos.index`)
- **Vistas:** Organizadas por módulo en estructura de carpetas
- **Compatibilidad:** Las rutas antiguas se pueden mantener temporalmente en `web.php` para transición gradual
- **API endpoints:** Se pueden reutilizar con prefijos de módulo

