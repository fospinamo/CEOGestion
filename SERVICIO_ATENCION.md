# Sistema de Atención de Servicios con Firma Digital

## 📋 Descripción General

El sistema de atención de servicios permite a los técnicos registrar completamente la atención brindada a un servicio, incluyendo:

- ✅ Descripción detallada del trabajo realizado
- ✅ Selección de equipos adicionales atendidos en la misma ubicación
- ✅ Captura de datos del receptor (nombre, apellido, documento)
- ✅ Firma digital del receptor en dispositivo móvil o PC
- ✅ Cierre automático del servicio con registro de auditoría

---

## 🚀 Cómo Usar

### 1. Iniciar la Atención de un Servicio

1. Navega a la vista del servicio (`/servicios/{id}`)
2. Haz clic en el botón verde **"Atender & Cerrar"** en la sección de Acciones
3. El botón solo aparece si el servicio NO está cerrado o cancelado

### 2. Completar el Formulario de Atención

#### 📌 Información del Servicio (Solo Lectura)
- Muestra el equipo principal siendo atendido
- Muestra la ubicación (Área y Sede)
- Muestra el tipo y descripción del problema

#### 🖥️ Seleccionar Equipos Adicionales
- Si hay otros equipos en la misma área en estado "OPERATIVO":
  - Se muestran como checkboxes
  - Marca los que también fueron atendidos
  - Se guardan en `equipos_adicionales_atendidos` como JSON

#### ✍️ Describir lo Realizado
- Campo de texto requerido (mínimo 20 caracteres)
- Incluye: diagnóstico, soluciones, repuestos, cambios, etc.
- Máximo 5000 caracteres

#### 👤 Datos del Receptor
- **Nombre**: Nombre de la persona que recibe el equipo
- **Apellido**: Apellido completo
- **Documento**: Cédula, DNI, pasaporte u otro documento de identidad

#### ✋ Firma Digital
- Captura de firma en canvas HTML5
- Compatible con:
  - 🖱️ Mouse/Trackpad
  - ✌️ Dedo (dispositivos táctiles)
  - 🖊️ Lápiz digital
- Botones:
  - **Limpiar**: Resetea la firma
  - **Deshacer**: Borra el último trazo

### 3. Enviar el Formulario
- Haz clic en **"Cerrar Servicio"**
- Validaciones automáticas:
  - Todos los campos requeridos deben estar completos
  - La firma no debe estar vacía
  - El documento debe ser válido
- Tras envío exitoso:
  - Se redirige al detalle del servicio
  - Se muestra mensaje: "Servicio atendido y cerrado correctamente. Firma capturada."

---

## 🗄️ Estructura de Datos

### Tabla: servicios
```sql
-- Campos nuevos añadidos:
persona_receptora_nombre VARCHAR(100) NULL
persona_receptora_apellido VARCHAR(100) NULL
persona_receptora_documento VARCHAR(50) NULL
firma_persona_receptora LONGTEXT NULL  -- Ruta a archivo PNG o base64
descripcion_atencion LONGTEXT NULL
equipos_adicionales_atendidos JSON NULL  -- Array de IDs: [1, 5, 12]
fecha_firma TIMESTAMP NULL
```

### Casts en Modelo
```php
protected $casts = [
    'equipos_adicionales_atendidos' => 'array',
    'fecha_firma' => 'datetime',
    // ... otros casts
];
```

---

## 🔧 Métodos del Modelo Servicio

### `equiposAdicionalesDisponibles()`
Retorna colección de equipos disponibles para seleccionar:
```php
$equipos = $servicio->equiposAdicionalesDisponibles();
// Filtra:
// - Misma área (area_id)
// - Diferente del equipo principal
// - Estado operativo = 'OPERATIVO'
```

### `getEquiposAtendidosRelacion()`
Convierte array de IDs a modelos Equipo:
```php
$equipos = $servicio->getEquiposAtendidosRelacion();
// Retorna colección de Equipo models desde equipos_adicionales_atendidos
```

---

## 🎯 Rutas Definidas

### GET - Mostrar formulario
```
GET /servicios/{servicio}/atender
Nombre: servicios.attend
Controlador: ServicioController@attend
```

### POST - Guardar atención
```
POST /servicios/{servicio}/atender
Nombre: servicios.storeAttendance
Controlador: ServicioController@storeAttendance
```

---

## 📝 Flujo de Datos

```
Usuario accede a servicio
    ↓
Haz clic "Atender & Cerrar"
    ↓
GET /servicios/{id}/atender
    ↓
Carga equiposAdicionalesDisponibles()
    ↓
Renderiza attend.blade.php
    ↓
Usuario completa forma y firma
    ↓
POST /servicios/{id}/atender (storeAttendance)
    ↓
Validación de campos
    ↓
Conversión base64 a archivo PNG
    ↓
Guardar en storage/app/public/firmas/
    ↓
Actualizar servicio:
  - Estado = CERRADO
  - Todos los campos de atención
  - equipos_adicionales_atendidos = JSON
    ↓
Crear SeguimientoServicio (CIERRE)
    ↓
Redirigir a servicios.show con éxito
```

---

## 🛡️ Validaciones

| Campo | Regla | Mensaje |
|-------|-------|---------|
| descripcion_atencion | required, min:20, max:5000 | "Debe describir lo realizado" |
| persona_receptora_nombre | required, max:100 | "Debe ingresar el nombre" |
| persona_receptora_apellido | required, max:100 | "Debe ingresar el apellido" |
| persona_receptora_documento | required, max:50 | "Debe ingresar el documento" |
| firma_persona_receptora | required, not empty | "Debe capturar la firma" |
| equipos_adicionales.* | exists:equipos,id | Validación automática |

---

## 💾 Almacenamiento de Firma

### Ubicación
```
storage/app/public/firmas/
    └── servicio_{servicio_id}_{timestamp}.png
```

### Ejemplo
```
storage/app/public/firmas/servicio_42_1713963600.png
```

### Pasos de Conversión
1. Canvas HTML5 → Base64 PNG
2. Validación: No estén vacíos
3. Decodificación base64
4. Guardado en storage con nombre único
5. Almacenamiento de ruta en DB

---

## 📱 Responsividad Móvil

### Canvas Signature
```css
@media (max-width: 768px) {
    #signatureCanvas {
        width: 100%;
        height: 150px;
    }
}
```

### Soporte táctil
- Touch eventos automáticamente soportados por SignaturePad
- No requiere configuración adicional
- Compatible con:
  - iOS Safari
  - Android Chrome/Firefox
  - Tablets
  - Stylus devices

---

## 🔗 Integración con SignaturePad.js

```html
<!-- CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<!-- Inicialización -->
const signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)',
    penColor: 'rgb(0, 0, 0)',
    minWidth: 1,
    maxWidth: 2.5,
});
```

---

## 🐛 Solución de Problemas

### Problema: "La firma no debe estar vacía"
**Solución**: Asegúrate de que el usuario realmente firme en el canvas antes de enviar

### Problema: "El equipo adicional no existe"
**Solución**: Verifica que los IDs de equipos sean válidos y existan en la BD

### Problema: Firma no se guarda como archivo
**Solución**: Verifica permisos de storage:
```bash
php artisan storage:link
chmod -R 755 storage/
```

### Problema: Canvas no funciona en móvil
**Solución**: 
- Verifica que la página carga en HTTPS o localhost
- SignaturePad requiere eventos de mouse/touch
- Limpia cache del navegador

---

## 📊 Datos Almacenados en Seguimiento

```php
SeguimientoServicio::create([
    'servicio_id' => $servicio->id,
    'user_id' => auth()->id(),
    'accion' => 'CIERRE',
    'observacion' => 'Servicio cerrado por: Juan Pérez',
    'metadata' => [
        'equipos_atendidos' => 2,
        'documento_receptor' => '1234567890',
    ]
]);
```

---

## 🚀 Casos de Uso

### Escenario 1: Reparación Simple
1. Equipo principal se repara
2. Solo firma del receptor
3. Se cierra servicio

### Escenario 2: Visita Preventiva
1. Se atiende equipo principal
2. Se revisan 3 equipos adicionales
3. Se actualizar estado de todos
4. Una firma por los 4 equipos

### Escenario 3: Cambio de Hardware
1. Se reemplaza componente
2. Se atienden 2 equipos relacionados
3. Se documenta con firma
4. Registro auditable

---

## 📋 Checklist de Validación Post-Implementación

- [x] Migraciones aplicadas
- [x] Modelo actualizado con campos
- [x] Controlador con métodos attend() y storeAttendance()
- [x] Rutas registradas correctamente
- [x] Vista attend.blade.php con SignaturePad
- [x] Botón agregado en servicios/show.blade.php
- [x] Validaciones funcionales
- [x] Almacenamiento de firmas
- [x] Auditoría con SeguimientoServicio
- [ ] Pruebas manuales completadas
- [ ] Pruebas en dispositivos móviles
- [ ] Manejo de errores exhaustivo

---

## 📞 Soporte y Contacto

Para issues o mejoras:
1. Verificar logs en `storage/logs/laravel.log`
2. Ejecutar `php artisan migrate:status`
3. Verificar permisos de almacenamiento
4. Validar disponibilidad de CDN para SignaturePad
