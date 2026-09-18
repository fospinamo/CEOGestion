# 🎯 REFERENCIA RÁPIDA - Variables de Configuración

**Actualizado**: 2026-07-15

---

## ⚙️ NUEVAS VARIABLES EN `.env`

### Síntesis Completa

```env
# ========================================
# DEEPSEEK API - Procesamiento de Voz con IA
# ========================================
DEEPSEEK_API_KEY=sk-...              # Tu clave API

# ✅ ACTIVAR/DESACTIVAR IA
ENABLE_IA_PROCESSING=true            # true=usa IA / false=sin IA

# ✅ ACTIVAR CACHÉ (AHORRA TOKENS)
IA_CACHE_ENABLED=true                # true=cachear / false=no cachear

# Proveedor (solo 'deepseek' por ahora)
IA_PROVIDER=deepseek

# Modelo
DEEPSEEK_MODEL=deepseek-chat         # Más barato

# Timeout
IA_API_TIMEOUT=30                    # Segundos
```

---

## 🎮 ESCENARIOS DE USO

### Escenario 1: Máximo Ahorro
```env
ENABLE_IA_PROCESSING=false           # Sin IA
IA_CACHE_ENABLED=false               # Sin caché
# COSTO: $0/mes
# FUNCIONALIDAD: Captura voz + acentuación local
```

### Escenario 2: Balanceado (RECOMENDADO)
```env
ENABLE_IA_PROCESSING=true            # Con IA
IA_CACHE_ENABLED=true                # Con caché
# COSTO: ~$0.02-0.20/mes (con 10 usuarios)
# FUNCIONALIDAD: Todo + revisión manual
```

### Escenario 3: Máxima Funcionalidad
```env
ENABLE_IA_PROCESSING=true            # Con IA
IA_CACHE_ENABLED=true                # Con caché
DEEPSEEK_MODEL=deepseek-coder        # Modelo premium
# COSTO: ~2-3x más caro
# FUNCIONALIDAD: Mejor calidad para código
```

---

## 🚀 CAMBIAR CONFIGURACIÓN

### Paso 1: Editar `.env`
```bash
nano c:\xampp\htdocs\CEOGestion\.env

# Cambiar la línea:
ENABLE_IA_PROCESSING=false
```

### Paso 2: Limpiar Caches
```bash
cd c:\xampp\htdocs\CEOGestion

php artisan cache:clear
php artisan config:clear
```

### Paso 3: Verificar
```bash
php artisan tinker

>>> config('ENABLE_IA_PROCESSING')
// Should return: true or false
```

---

## 🔍 VERIFICAR ESTADO

### Ver si IA está habilitada
```bash
php artisan tinker

>>> env('ENABLE_IA_PROCESSING')
=> true

>>> env('IA_CACHE_ENABLED')
=> true

>>> env('DEEPSEEK_MODEL')
=> "deepseek-chat"
```

### Ver logs de uso
```bash
tail -f storage/logs/laravel.log

# Buscar "Usando respuesta en caché"
# o "Llamando Deepseek API"
```

---

## 💰 ESTIMADOR DE COSTOS

**Basado en:** número de usuarios × dictados/día

```
│ Usuarios │ Dictados/Día │ Tokens/Mes │ Costo/Mes │ Anual   │
├──────────┼──────────────┼────────────┼───────────┼─────────┤
│ 1-5      │ 2            │ 18K        │ $0        │ $0      │
│ 5-10     │ 5            │ 45K        │ $0        │ $0      │
│ 10-20    │ 10           │ 90K        │ $0        │ $0      │
│ 20-50    │ 20           │ 180K       │ $0.01     │ $0.12   │
│ 50-100   │ 50           │ 450K       │ $0.03     │ $0.36   │
│ 100+     │ 100+         │ 900K+      │ $0.06+    │ $0.72+  │

Con CACHÉ activado (80% reutilización):
Multiplica costo por 0.2 (divide entre 5)
```

---

## ✅ CHECKLIST DE SETUP

### Instalación Inicial

- [ ] 1. Obtener API Key en https://platform.deepseek.com/
- [ ] 2. Abrir `.env` y agregar `DEEPSEEK_API_KEY=sk-...`
- [ ] 3. Agregar variables de configuración
- [ ] 4. Ejecutar `php artisan cache:clear`
- [ ] 5. Ejecutar `php artisan config:clear`
- [ ] 6. Probar en navegador

### Optimización

- [ ] 1. Activar caché: `IA_CACHE_ENABLED=true`
- [ ] 2. Monitorear uso en https://platform.deepseek.com/usage
- [ ] 3. Ajustar `IA_API_TIMEOUT` si es necesario
- [ ] 4. Revisar logs: `tail -f storage/logs/laravel.log`

### Seguridad

- [ ] 1. `.env` está en `.gitignore`
- [ ] 2. API Key es secreta (no compartir)
- [ ] 3. Backups de `.env` guardados
- [ ] 4. Permisos correctos en `storage/`

---

## ❓ FAQ RÁPIDO

**¿Cómo desactivo IA temporalmente?**
```env
ENABLE_IA_PROCESSING=false
```
Luego: `php artisan cache:clear && php artisan config:clear`

**¿Cómo ahorro más tokens?**
```env
IA_CACHE_ENABLED=true              # Básico
IA_API_TIMEOUT=15                  # Reducir timeout
```

**¿Qué pasa si no tengo API Key?**
```
Sin API Key + ENABLE_IA_PROCESSING=true
→ Error 503 (Servicio no disponible)

Solución: Desactivar IA o configurar API Key
```

**¿Los datos se envían a Deepseek?**
```
Sí, el texto se envía a servidores Deepseek
para procesarlo. NO se guardan allá.
```

**¿Puedo usar offline?**
```
Captura de voz: ✅ SÍ (local)
Procesamiento IA: ❌ NO (requiere internet servidor)

Solución: ENABLE_IA_PROCESSING=false
```

---

## 📞 TROUBLESHOOTING

### Error: "DEEPSEEK_API_KEY no configurada"
```bash
# Verificar:
grep DEEPSEEK_API_KEY .env

# Debe aparecer:
DEEPSEEK_API_KEY=sk-...
```

### Error: "Proveedor IA no configurado"
```bash
# Verificar:
grep IA_PROVIDER .env

# Debe ser:
IA_PROVIDER=deepseek
```

### El botón 🤖 no aparece
```bash
# Limpiar caches:
php artisan cache:clear
php artisan view:clear

# Recargar navegador: Ctrl+F5
```

### Modal de confirmación no funciona
```bash
# Abrir DevTools (F12)
# Console tab
# Ver si hay errores JavaScript
```

---

**¡Configuración completa! 🎉**

Referencia rápida para cambios y troubleshooting.
