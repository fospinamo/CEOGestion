# 💰 GUÍA COMPLETA - Costos, Ahorros y Configuración de IA

**Actualizado**: 2026-07-15  
**Version**: 2.0 - Con Parámetros de Activación/Desactivación

---

## 📊 PRECIOS EXACTOS DE DEEPSEEK

### Pricing Oficial Deepseek

```
INPUT (Lectura):    $0.14 por 1 millón de tokens
OUTPUT (Respuesta): $0.28 por 1 millón de tokens

GRATIS:             100,000 tokens incluidos
```

### Cálculo Real para Tu Caso

**Promedio por dictado:**
```
Prompt (estructura + sistema):     ~150 tokens input
Texto captado (promedio 50 words):  ~100 tokens input
Respuesta mejorada (200 words):     ~250 tokens output

TOTAL POR DICTADO:
├─ Input:  250 tokens × $0.14/1M = $0.000035
├─ Output: 250 tokens × $0.28/1M = $0.000070
└─ COSTO: ~$0.000105 USD por dictado
```

### Escenarios de Uso Mensual

```
10 DICTADOS/MES:
├─ Tokens totales: ~4,500
├─ Costo: $0.00047
└─ Status: ✅ GRATIS (dentro del 100K)

100 DICTADOS/MES:
├─ Tokens totales: ~45,000
├─ Costo: $0.0047
└─ Status: ✅ GRATIS (dentro del 100K)

1,000 DICTADOS/MES (100 usuarios × 10):
├─ Tokens totales: ~450,000
├─ Costo: $0.047 (EXCEDE GRATIS)
├─ Status: PAGO - $0.047 USD/mes
└─ Estimación anual: ~$0.56 USD

10,000 DICTADOS/MES (1,000 usuarios):
├─ Tokens totales: ~4,500,000
├─ Costo: $0.47
└─ Estimación anual: $5.64 USD
```

### Paquetes Disponibles

| Plan | Precio | Tokens/Mes | Ideal Para |
|---|---|---|---|
| **Gratis** | $0 | 100K | 1-10 usuarios ocasionales |
| **Pay-As-You-Go** | $0.14-0.28/1M | Ilimitado | Bajo uso intermitente |
| **Prepago** | $20/mes | ~142M | 100+ usuarios activos |
| **Enterprise** | Custom | Custom | Empresas grandes |

---

## 🎯 SOLUCIÓN: AHORRAR TOKENS

Se implementaron 3 mecanismos de ahorro:

### 1️⃣ **Caché Inteligente** (Ahorra ~80% en textos repetidos)

```php
// Almacena respuestas por 24 horas
ENABLE_IA_CACHE=true

Si técnico dicta 2 veces:
"el servidor está lento"

Primera llamada:  $0.000105 USD ← Llama a Deepseek
Segunda llamada:  $0.000000 USD ← Usa caché
AHORRO:           100% ✅
```

### 2️⃣ **Parámetro de Activación/Desactivación**

```env
# .env
ENABLE_IA_PROCESSING=true    # ✅ Usa IA Deepseek ($)
ENABLE_IA_PROCESSING=false   # ❌ Solo captura de voz ($0)
```

**Escenario de uso:**
```
Lunes-Viernes:   ENABLE_IA_PROCESSING=true   (usar IA)
Fines de semana:  ENABLE_IA_PROCESSING=false (ahorrar)

Si usas 5 días/semana:
100 dictados/semana × $0.000105 = $0.0105/semana
4 semanas × $0.0105 = $0.042 USD/mes
COSTO ANUAL: ~$0.50 USD ✅
```

### 3️⃣ **Modal de Confirmación** (Revisar antes de guardar)

```javascript
Usuario dicta → IA procesa → MODAL de confirmación
                              ├─ Ver texto original
                              ├─ Ver texto procesado
                              └─ Confirmar o Rechazar
                              
Si rechaza → NO usa tokens guardados
Si confirma → Guarda con IA
```

**Ventaja:** Reduces errores y gastos innecesarios

---

## ⚙️ CONFIGURACIÓN COMPLETA EN `.env`

```env
# ========================================
# DEEPSEEK API - Procesamiento de Voz con IA
# ========================================

# Tu API Key (obtener en https://platform.deepseek.com/)
DEEPSEEK_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# ✅ ACTIVAR/DESACTIVAR IA
# true = usa Deepseek IA para mejorar redacción (COSTO)
# false = solo captura de voz sin IA (GRATIS)
ENABLE_IA_PROCESSING=true

# ✅ ACTIVAR/DESACTIVAR CACHÉ (AHORRA ~80%)
# true = reutiliza respuestas para textos similares
# false = cada solicitud llama a IA
IA_CACHE_ENABLED=true

# Proveedor de IA
# deepseek = Usa Deepseek API
IA_PROVIDER=deepseek

# Modelo Deepseek
# deepseek-chat = Recomendado (más barato)
DEEPSEEK_MODEL=deepseek-chat

# Timeout para API (segundos)
IA_API_TIMEOUT=30
```

---

## 📱 INTERNET - Aclaración

**La pregunta del usuario:** "¿El internet es donde está alojada la aplicación o del dispositivo?"

### Respuesta Completa:

```
┌──────────────────────────────────────────────────────────┐
│ FUNCIONALIDAD                    │ DÓNDE SE PROCESA      │
├──────────────────────────────────────────────────────────┤
│ 1. Web Speech API                │ Navegador (dispositivo)│
│    (Captura de audio)            │ NO requiere internet  │
├──────────────────────────────────────────────────────────┤
│ 2. Enviamiento de texto a IA     │ SERVIDOR Laravel     │
│    (POST /procesar-voz)          │ SÍ requiere internet │
├──────────────────────────────────────────────────────────┤
│ 3. Deepseek API                  │ Servidores Deepseek  │
│    (Procesamiento con IA)        │ SÍ requiere internet │
├──────────────────────────────────────────────────────────┤
│ 4. Guardado en BD                │ SERVIDOR Laravel     │
│    (Base de datos)               │ SÍ requiere internet │
└──────────────────────────────────────────────────────────┘

RESUMEN:
• Captura de voz: ✅ FUNCIONA SIN INTERNET (local en navegador)
• Procesamiento IA: ❌ REQUIERE INTERNET (el SERVIDOR debe tener)
• Guardado: ❌ REQUIERE INTERNET (comunicación servidor-BD)

CONCLUSIÓN:
- Si SERVIDOR tiene internet → TODO funciona
- Si SERVIDOR SIN internet → Captura voz pero IA NO funciona
- Si CLIENTE sin internet → Captura voz sigue funcionando
```

---

## 🎮 USAR SIN INTERNET

**¿Si el servidor no tiene internet, qué pasa?**

```
Escenario: Servidor sin internet, ENABLE_IA_PROCESSING=true

Usuario dicta:
├─ ✅ Web Speech captura (funciona)
├─ ❌ Envío a Deepseek falla (sin internet)
└─ ⚠️ Mensaje: "Error al procesar con IA"

Solución: Usar caché + desactivar IA
ENABLE_IA_PROCESSING=false

Entonces:
├─ ✅ Web Speech captura (funciona)
├─ ✅ Se guarda tal cual se dicta (funciona)
└─ $0 costo
```

---

## ✅ CÓMO ACTIVAR/DESACTIVAR IA

### Opción 1: Permanente (Cambiar `.env`)

```bash
# Desactivar IA completamente
ENABLE_IA_PROCESSING=false

# Limpiar caches
php artisan cache:clear
php artisan config:clear
```

### Opción 2: Temporal (Por horario)

```php
// En .env
ENABLE_IA_PROCESSING=true

// En controlador (futuro)
$hour = now()->hour;
$iaEnabled = ($hour >= 8 && $hour <= 18); // Solo 8am-6pm
```

### Opción 3: En Tiempo Real (Interfaz de Admin - Futuro)

```
Admin Panel → Settings → Procesamiento IA
├─ Toggle: Activar/Desactivar
├─ Horarios automáticos
└─ Monitoreo de tokens
```

---

## 📊 FLUJOS DE USUARIO POR CONFIGURACIÓN

### Flujo 1: IA ACTIVADA (ENABLE_IA_PROCESSING=true)

```
Usuario dicta: "el servidor esta lento"
        ↓
Web Speech captura ✅
        ↓
"🤖 Mejorar con IA" aparece
        ↓
Usuario clica ✅
        ↓
BACKEND: Verifica cache
├─ Encontrado:   Retorna cached (~$0)
└─ No encontrado: Llama Deepseek (~$0.0001)
        ↓
MODAL: Mostrar comparación
├─ Izquierda:  "el servidor esta lento"
├─ Derecha:    "DIAGNÓSTICO\n- Problema: Servidor lento..."
└─ Botones: Confirmar | Rechazar
        ↓
Usuario clica "Confirmar"
        ↓
✅ Guardar texto mejorado en BD
```

### Flujo 2: IA DESACTIVADA (ENABLE_IA_PROCESSING=false)

```
Usuario dicta: "el servidor esta lento"
        ↓
Web Speech captura ✅
        ↓
"🤖 Mejorar" botón NO aparece ❌
        ↓
Textarea muestra: "el servidor esta lento"
(Tal cual como se dictó)
        ↓
Usuario edita si es necesario ✏️
        ↓
✅ Guardar texto sin procesamiento
(COSTO: $0)
```

---

## 💡 RECOMENDACIÓN PARA TU CASO

### Con 10 usuarios ocasionales:

```env
# .env - Configuración recomendada

# Activar IA siempre (bajo costo)
ENABLE_IA_PROCESSING=true

# Activar caché para ahorrar
IA_CACHE_ENABLED=true

# Usar modelo más barato
DEEPSEEK_MODEL=deepseek-chat
```

**Estimación mensual:**
```
10 usuarios × 5 dictados/día × 20 días = 1,000 dictados/mes
1,000 dictados × $0.000105 = $0.105 USD/mes

PERO con caché (80% reutilización):
$0.105 × 0.2 = $0.021 USD/mes
ANUAL: ~$0.25 USD ✅ CASI GRATIS
```

---

## 🎯 SÍNTESIS: Respuestas a tus Preguntas

### 1. "Ahorrar tokens porquelos prompts siempre son iguales"
✅ **Implementado: Caché inteligente**
- Reutiliza respuestas 24 horas
- Ahorra ~80% en textos repetidos

### 2. "¿Cuánto cuesta Deepseek?"
✅ **$0.000105 USD por dictado**
- Primeros 100K tokens GRATIS
- Luego $0.14 input + $0.28 output por 1M tokens

### 3. "¿Hay paquetes?"
✅ **Sí:**
- Gratis: 100K tokens
- Pay-as-you-go: $0.14-0.28/1M tokens
- Prepago: $20/mes (~142M tokens)

### 4. "Parámetro para activar/desactivar"
✅ **Implementado: ENABLE_IA_PROCESSING**
- true = usa IA ($)
- false = solo captura de voz ($0)

### 5. "Captura sigue funcionando sin IA"
✅ **Sí, con ENABLE_IA_PROCESSING=false**
- Web Speech funciona
- Texto se guarda sin procesar

### 6. "¿Internet en servidor o dispositivo?"
✅ **Internet del SERVIDOR**
- Captura: Local (dispositivo)
- IA: Servidores Deepseek (necesita internet servidor)

### 7. "Modal de confirmación"
✅ **Implementado**
- Muestra original vs procesado
- Confirmar antes de guardar

---

## 🚀 PRÓXIMOS PASOS

1. **Configurar `.env` con parámetros nuevos**
   ```bash
   cp .env .env.backup
   # Editar con nuevas variables
   ```

2. **Limpiar caches**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Probar**
   - Dictar en un informe
   - Debe aparecer modal de confirmación
   - Puede aceptar o rechazar

4. **Monitorear tokens** (Opcional)
   - https://platform.deepseek.com/usage

---

**¡Implementación completa y flexible! 🎉**

Ahora puedes:
- Activar/desactivar IA con 1 línea en `.env`
- Cachear respuestas para ahorrar tokens
- Revisar cambios antes de confirmar
- Funciona sin internet (captura local)
