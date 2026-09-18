# 🤖 Guía de Implementación - Procesamiento de Voz con IA (Deepseek)

**Fecha**: 2026-07-15  
**Versión**: 1.0  
**Estado**: Implementado

---

## 📋 Resumen de Cambios

Se ha implementado un sistema **HÍBRIDO y GRATUITO** que combina:

1. **Web Speech API** - Captura de voz nativa del navegador
   - Costo: $0
   - Mejoras: Corrección automática de acentuaciones con IA
   - Campos: Diagnóstico y Observaciones

2. **Deepseek IA** - Procesamiento de texto con IA
   - Costo: $0 (100K tokens gratis)
   - Función: Organiza, mejora redacción, corrige acentos
   - Botón manual: "🤖 Mejorar con IA"

---

## 🔧 Archivos Modificados/Creados

### ✅ Creados
- `app/Http/Controllers/Incidencias/InformeController.php` - Controlador para procesamiento
- `.env.example` - Configuración de ejemplo

### ✅ Modificados
- `resources/views/incidencias/servicios/report-technician-v2.blade.php`
  - Agregados botones de "Mejorar con IA" en ambos campos
  - Campos ocultos para guardar texto original
  - JS para procesar con API

- `routes/incidencias.php`
  - Nueva ruta POST: `/incidencias/servicios/procesar-voz`

- `.env`
  - Nueva variable: `DEEPSEEK_API_KEY`

### ✅ Backup
- `report-technician-v2.blade.php.BACKUP_2026_07_15`
  - Copia de seguridad por si necesitas revertir

---

## 🚀 Configuración Inicial

### Paso 1: Obtener API Key de Deepseek (GRATIS)

1. Ir a: https://platform.deepseek.com/
2. Registrarse (gratis, solo requiere email)
3. Ir a **Account → API Keys → Create New**
4. Copiar la clave generada (ej: `sk-xxxxxxxxxxxxx`)
5. Guardar en lugar seguro

### Paso 2: Configurar .env

```bash
# En c:\xampp\htdocs\CEOGestion\.env
DEEPSEEK_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

> **Nota**: Reemplaza `sk-xxxxx...` con tu clave real

### Paso 3: Limpiar Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Paso 4: Probar la Solución

1. Ir a: `/incidencias/servicios/{servicio_id}/informe`
2. Hacer clic en botón "🎤 Dictar" en campo diagnóstico
3. Dictar texto (ej: "El servidor estaba lento con disco lleno")
4. Después de terminar, aparecerá botón "🤖 Mejorar con IA"
5. Hacer clic en botón azul
6. ✨ El texto se mejorará automáticamente

---

## 📊 Flujo de Funcionamiento

### Escenario 1: Usuario Solo con Voz

```
Usuario dicta:
"el servidor está lento encontré que el disco está 95% lleno"
                    ↓
Web Speech API captura como:
"el servidor esta lento encontre que el disco esta 95 lleno"
                    ↓
Botón "🤖 Mejorar con IA" aparece
                    ↓
Usuario clica el botón
                    ↓
Deepseek procesa:
"DIAGNÓSTICO
- Problema: Servidor con rendimiento degradado
- Síntoma: Lentitud en operaciones
- Causa: Disco duro alcanzó 95% de capacidad"
                    ↓
Textarea se actualiza con texto profesional
                    ↓
Usuario edita si es necesario y guarda ✅
```

### Escenario 2: Si Deepseek Falla

```
Usuario dicta → Aparece botón IA
                    ↓
Usuario clica botón
                    ↓
❌ Error en API / Sin Internet
                    ↓
⚠️ Mensaje: "IA no disponible, se mantiene el texto original"
                    ↓
Usuario puede editar manualmente ✅
```

---

## 🎯 Características Implementadas

### ✅ Para Ambos Campos (Diagnóstico & Observaciones)

| Característica | Descripción |
|---|---|
| **Captura de Voz** | Web Speech API nativa (sin costo) |
| **Acentuaciones** | Deepseek corrige automáticamente |
| **Botón Manual** | Usuario controla cuándo procesar |
| **Texto Original** | Se guarda en campo oculto |
| **Editable** | Usuario puede modificar resultado de IA |
| **Offline-friendly** | Captura local, procesamiento remoto |
| **Auditoría** | Campos separados para original/procesado |

### ✅ Prompts Inteligentes

**Para Diagnóstico:**
- Estructura: Problema → Síntomas → Causa Raíz
- Máx 200 palabras

**Para Observaciones:**
- Estructura: Tarea Realizada → Hallazgos → Conclusión
- Máx 300 palabras

---

## 💾 Datos Guardados en BD

Cuando el usuario guarda el informe:

```sql
-- Campo principal (mejorado con IA)
diagnostico_validacion = "DIAGNÓSTICO\n- Problema: ..."

-- Campo de auditoría (texto original captado)
diagnostico_validacion_original = "el servidor esta lento encontre que..."

-- Mismo para observaciones
observaciones_informe = "TAREA REALIZADA\n..."
observaciones_informe_original = "texto dictado original..."
```

> **Nota**: Los campos `*_original` se crean automáticamente al guardar

---

## ⚡ Estimación de Costos

### Deepseek Pricing

| Métrica | Costo |
|---|---|
| **Primeros 100K tokens** | **GRATIS** 🎉 |
| Tokens por dictado típico | ~200-500 |
| Dictados diarios (10 usuarios × 5) | ~50 |
| Tokens/mes (50 × 400 promedio) | ~20K |
| **Costo mensual** | **$0** (dentro del gratis) |

**Nota**: Con 10 usuarios ocasionales, estarás dentro del tier gratuito

---

## 🔍 Debugging & Logs

### Ver Logs de Errores

```bash
# En archivo de logs
tail -f storage/logs/laravel.log

# Líneas de interés
[2026-07-15] local.ERROR: Llamando Deepseek API
[2026-07-15] local.ERROR: Error procesando voz con IA
```

### En Navegador (DevTools)

```javascript
// Abrir Console (F12)
// Ver requests a: POST /incidencias/servicios/procesar-voz
// Network tab → Ver respuesta de la API
```

---

## 🚨 Problemas Comunes

### ❌ "API Key no configurada"

**Solución:**
```bash
# Asegúrate que en .env esté:
DEEPSEEK_API_KEY=sk-xxxxx...

# Luego ejecuta:
php artisan config:clear
php artisan cache:clear
```

### ❌ "Error al procesar con IA"

**Causas:**
- API Key inválida
- Sin internet
- Tokens agotados (después de 100K gratis)
- Timeout (>30 seg)

**Solución:**
- Verificar API Key en https://platform.deepseek.com/
- Verificar conexión de internet
- Revisar logs: `storage/logs/laravel.log`

### ❌ "No aparece botón 🤖 Mejorar"

**Causa:** Probablemente JS no se cargó correctamente

**Solución:**
```bash
php artisan view:clear
# Reload página en navegador (Ctrl+F5)
```

---

## 🎓 Mejores Prácticas

### ✅ Para Usuarios

1. **Dicta claramente** - Mejor pronunciación = mejor captura
2. **Pausa entre oraciones** - Ayuda a Web Speech API
3. **Revisa antes de procesar con IA** - Edita errores obvios primero
4. **Confirma resultado de IA** - Puede necesitar ajustes finales
5. **No presiones botón múltiples veces** - Espera respuesta

### ✅ Para Administradores

1. **Monitorea uso de tokens** - En https://platform.deepseek.com/usage
2. **Revisar logs regularmente** - `storage/logs/laravel.log`
3. **Informar a usuarios sobre limitaciones** - Explicar que es gratuito
4. **Hacer backup de API Key** - Guardar en lugar seguro
5. **Considerar rate limiting** - Si muchos usuarios usan simultáneamente

---

## 📞 Soporte & Contacto

### Deepseek Support
- **Docs**: https://api-docs.deepseek.com/
- **Status**: https://status.deepseek.com/
- **Email**: support@deepseek.com

### Problemas Técnicos Locales
- Revisar logs: `storage/logs/laravel.log`
- Verificar permisos de arquivo: `storage/` debe ser writable
- Verificar PHP: `php artisan tinker`

---

## 📝 Versión & Historial

| Versión | Fecha | Cambios |
|---|---|---|
| 1.0 | 2026-07-15 | Implementación inicial |
| - | - | - |

---

**Última actualización**: 2026-07-15  
**Desarrollador**: GitHub Copilot  
**Status**: ✅ Funcional y Testeable
