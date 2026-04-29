# 📱 Mobile Testing Guide - CEOGestion Informe Técnico

## Quick Start - Testing Responsive Design

### Para Testear en tu Computadora (Chrome DevTools)

#### Paso 1: Abrir DevTools
```
Presiona: F12 (o Ctrl+Shift+I)
Verás: Panel de desarrollador abierto
```

#### Paso 2: Activar Responsive Mode
```
Presiona: Ctrl+Shift+M (o Cmd+Shift+M en Mac)
Verás: Selector de dispositivos en la esquina superior izquierda
```

#### Paso 3: Seleccionar Dispositivo
```
Click en "Responsive" dropdown
Selecciona:
  ✅ iPhone SE (375px x 667px)
  ✅ iPhone 12 Pro (390px x 844px)
  ✅ Pixel 5 (412px x 915px)
  ✅ iPad (768px x 1024px)
```

#### Paso 4: Probar Informe Técnico
```
URL: http://localhost:8000/servicios/1/informe-tecnico
(Cambia el "1" por el ID del servicio que necesites)

Pruebas:
1. ✅ Página carga completa
2. ✅ Texto legible (no cutoff)
3. ✅ Inputs accesibles (puedes hacer click)
4. ✅ Canvas visible (rectángulo blanco para firma)
5. ✅ Botones grandes (mínimo 44x44px)
6. ✅ Tabla de repuestos: puedes scroll horizontalmente
7. ✅ Imágenes: no overflow
```

---

## Testing en Dispositivo Real - WiFi

### Obtener IP del Servidor

#### Paso 1: Abrir PowerShell (Windows)
```powershell
# Presiona: Windows + R
# Escribe: powershell
# Presiona: Enter
```

#### Paso 2: Obtener IP
```powershell
ipconfig | findstr /i "IPv4 Address"

# Resultado esperado:
# Dirección IPv4. . . . . . . . . . . . . . : 192.168.X.X
```

### Conectar Móvil a WiFi

#### Paso 1: Mismo WiFi
- Asegúrate que tu móvil esté conectado a la **misma red WiFi** que tu computadora
- Puedes verificar la IP de WiFi en Configuración → Red

#### Paso 2: Acceder en Navegador Móvil
```
URL: http://192.168.1.145:8000/
(Cambia 192.168.1.145 por tu IP real)
```

#### Paso 3: Login
```
Email: tecnico@ceogestion.test
Contraseña: password
(O usa el usuario técnico que tengas disponible)
```

#### Paso 4: Navegar a Informe
```
Menu → Servicios Asignados → Abrir un servicio
Botón: "Generar Informe Técnico" o "Informe Técnico"
```

---

## Casos de Prueba Detallados

### 1. Testing Canvas (Firma Digital)

**Escenario**: Dibuja firma en móvil
```
Paso 1: Scroll hasta sección "Firma Digital"
Paso 2: Verifica que canvas sea visible (rectángulo blanco)
Paso 3: Dibuja línea con dedo (en móvil) o mouse (en DevTools)
Paso 4: Verifica que línea se dibuje correctamente
Paso 5: Verifica que estado cambie a "✅ Firma lista"
Paso 6: Presiona botón "Limpiar" → firma se borra
Paso 7: Dibuja nueva firma
```

**Resultado Esperado**:
- ✅ Canvas ocupa 100% del ancho (excepto márgenes)
- ✅ Altura: 120px (móvil), 150px (desktop)
- ✅ Firma se ve nítida (no pixelada)
- ✅ Responde al toque inmediatamente
- ✅ No hay zoom al tocar

### 2. Testing Rotación (Orientación)

**Escenario**: Rota dispositivo portrait ↔ landscape
```
Paso 1: Abre informe en portrait (vertical)
Paso 2: Dibuja firma
Paso 3: Rota a landscape (horizontal)
Paso 4: Verifica que canvas se redimensione automáticamente
Paso 5: Dibuja firma adicional en landscape
Paso 6: Vuelve a portrait
Paso 7: Firma está intacta
```

**Resultado Esperado**:
- ✅ Canvas se redimensiona suavemente (sin saltos)
- ✅ No se pierde la firma anterior
- ✅ Nuevo trazo se dibuja correctamente
- ✅ No hay lag o congelación

### 3. Testing Tabla Repuestos

**Escenario**: Agregamosreparos en móvil
```
Paso 1: Scroll a sección "Repuestos Utilizados"
Paso 2: Presiona botón "+ Agregar Repuesto"
Paso 3: Verifica que fila se agregue
Paso 4: Intenta llenar campos:
        - Código
        - Descripción
        - Marca
        - Modelo
        - Serie
        - Cantidad
Paso 5: Verifica que puedas hacer scroll horizontal si es necesario
Paso 6: Presiona botón 🗑️ para eliminar fila
Paso 7: Fila desaparece
```

**Resultado Esperado**:
- ✅ Campos legibles (texto 12-14px)
- ✅ Inputs tienen altura suficiente para tocar (44px+)
- ✅ Padding compacto pero no crowded
- ✅ Puedes scroll horizontal para ver todas columnas
- ✅ Botón eliminar es fácil de presionar (no muy pequeño)

### 4. Testing Carga de Imágenes

**Escenario**: Subes fotos en móvil
```
Paso 1: Scroll a sección "Fotografías del Servicio"
Paso 2: Presiona "Seleccionar Imágenes"
Paso 3: Selecciona foto de cámara o galería
Paso 4: Verifica que preview se muestre correctamente
Paso 5: Selecciona múltiples imágenes (2-3)
Paso 6: Verifica que se muestren en grid
Paso 7: Presiona × en imagen para eliminar
```

**Resultado Esperado**:
- ✅ Input accesible (no necesita zoom)
- ✅ Preview visible sin scroll horizontal
- ✅ Imágenes se escalan correctamente
- ✅ Grid responsive (1-2 columnas según dispositivo)
- ✅ Botón eliminar accesible

### 5. Testing Form Submission

**Escenario**: Completa y envía formulario
```
Paso 1: Completa todos los campos requeridos:
        - Fecha de Atención
        - Hora Inicio
        - Hora Fin
        - Tipo de Servicio
        - Descripción Solicitud (mín 10 caracteres)
        - Diagnostico (mín 10 caracteres)
        - Pendientes
        - Observaciones
        - Firma (dibuja)
        - Nombre Receptor
Paso 2: Verifica que botón "Enviar" sea visible
Paso 3: Verifica que botón tenga altura de 44px+
Paso 4: Presiona botón "Enviar"
Paso 5: Verifica confirmación o error
```

**Resultado Esperado**:
- ✅ Todos campos visibles sin scroll excesivo
- ✅ Botón "Enviar" en bottom, accesible
- ✅ Validación clara de errores
- ✅ Confirmación de éxito
- ✅ No hay doble envío

---

## Responsive Breakpoints - Qué Ver

### En Móvil (< 640px) - iPhone SE, Pixel 5
```
✅ Texto en 12-14px (legible sin zoom)
✅ Padding compacto: 8px horizontal
✅ Canvas: 120px altura
✅ Tabla: 1 columna principal, scroll horizontal
✅ Botones: 44px altura
✅ Espaciado: Tight pero usable
```

### En Tablet (640px - 1024px) - iPad Mini
```
✅ Texto en 14-16px
✅ Padding moderado: 12px horizontal
✅ Canvas: 140px altura
✅ Tabla: 2-3 columnas visibles
✅ Botones: 44px+ altura
✅ Layout: 2 columnas donde aplica
```

### En Desktop (1024px+) - Laptop
```
✅ Texto en 16px+
✅ Padding generoso: 16-24px
✅ Canvas: 150px altura
✅ Tabla: Todas las columnas sin scroll
✅ Botones: 44px+ altura
✅ Layout: 3 columnas donde aplica
```

---

## Debugging Tips

### Si canvas se ve muy pequeño
```javascript
// En DevTools → Console, ejecuta:
console.log('Canvas width:', canvas.offsetWidth);
console.log('Canvas height:', canvas.offsetHeight);
console.log('DPR:', window.devicePixelRatio);

// Resultado esperado:
// Canvas width: ~360px (móvil)
// Canvas height: 120-150px
// DPR: 2 (Retina) o 1 (normal)
```

### Si firma se ve pixelada
```
Causa: Device Pixel Ratio no está siendo aplicado
Solución: Verifica que ctx.scale(dpr, dpr) esté en el código
Confirma: Abre DevTools → Elements → encuentra canvas
          Verifica que width/height sean 2x o 2.75x el tamaño visual
```

### Si canvas no responde al toque
```
Causa: touch-action no está configurado
Solución: Verifica en DevTools que canvas tenga style="touch-action: none"
Alternativa: Agrega en CSS: canvas { touch-action: none; }
```

### Si tabla tiene scroll horrible
```
Causa: Padding muy grande en móvil
Solución: Ya está arreglado con px-2 sm:px-3 md:px-4
Verificación: Abre DevTools, selecciona celda, verifica "Computed" tab
```

---

## Performance Testing

### Antes de Testing
```bash
# Abre Developer Console
F12 → Network tab

# Selecciona "Slow 3G" o "Fast 3G"
Esto simula conexión móvil lenta
```

### Medir Carga
```
Ideal: < 3 segundos (en 4G)
Aceptable: 3-5 segundos
Problema: > 5 segundos

Si es muy lenta:
1. Verificar tamaño de imágenes
2. Verificar lazy loading
3. Verificar scripts bloqueantes
```

### Medir Canvas Performance
```javascript
// En Console, ejecuta:
performance.mark('start-canvas');
ajustarCanvasTamano();
performance.mark('end-canvas');
performance.measure('canvas-measure', 'start-canvas', 'end-canvas');
console.log(performance.getEntriesByName('canvas-measure')[0].duration);

// Resultado esperado: < 5ms
// Si > 10ms: hay problema de performance
```

---

## Checklist Final

### Antes de Considerar "Complete"

#### Mobile (Vertical)
- [ ] Canvas completamente visible
- [ ] Puedo firmar sin zoom automático
- [ ] Tabla de repuestos es usable
- [ ] Puedo agregar/eliminar repuestos
- [ ] Imágenes cargan correctamente
- [ ] Formulario es completable sin scroll excesivo
- [ ] Botón "Enviar" visible y clickeable
- [ ] Validación funciona

#### Mobile (Horizontal)
- [ ] Canvas se redimensiona automáticamente
- [ ] Firma anterior se mantiene
- [ ] Puedo continuar trabajando
- [ ] No hay saltos o parpadeos

#### Tablet
- [ ] Layout a 2-3 columnas
- [ ] Espaciado adecuado
- [ ] Canvas visible sin necesidad de resize

#### Desktop
- [ ] Layout a 3 columnas completo
- [ ] Máximo ancho respetado (max-w-6xl)
- [ ] Todo legible y accesible

---

## Comandos Útiles

### Abrir Browser con DevTools Pre-abierto
```bash
# Chrome
google-chrome --remote-debugging-port=9222 http://localhost:8000

# Para testing remoto desde otra computadora
# Abre chrome://inspect en tu navegador
```

### SSH Para Debugging Remoto
```bash
# Si necesitas SSH al servidor desde móvil
# (Útil para ver logs en tiempo real)
ssh usuario@192.168.1.145 "tail -f storage/logs/laravel.log"
```

### Clear Cache Remoto
```bash
# Desde terminal en la computadora
php artisan cache:clear && php artisan view:clear

# Luego recarga en móvil (Ctrl+F5 en navegador)
```

---

## Problemas Comunes y Soluciones

### "El canvas se ve cortado"
```
1. Verifica que container tenga max-width: 100%
2. Verifica que padding sea responsive (px-2 sm:px-3)
3. Recarga la página (caché viejo)
4. Abre DevTools → Responsive mode → selecciona dispositivo
```

### "No puedo firmar en móvil"
```
1. Verifica touch-action: none está presente
2. Intenta limpiar firma y volver a intentar
3. Asegúrate que el área blanca es tocable
4. Si usa mouse en DevTools → Click y drag
5. Si usa touchscreen real → Dedo limpio y presión constante
```

### "Tabla de repuestos se ve gigante"
```
1. Verifica que padding esté en px-2 (móvil)
2. Recarga con cache:clear
3. Abre DevTools → selecciona tabla
4. Verifica computed width
5. Si aún es grande → reduce px-3 a px-2
```

### "Form no se envía"
```
1. Verifica que todos campos required están llenos
2. Verifica que firma está dibujada (status verde)
3. Abre DevTools → Console → busca errores
4. Abre DevTools → Network → busca el POST request
5. Verifica response (200 ok vs error)
```

---

## Próximos Pasos

1. **Testing Real Device**: Toma teléfono real, accede por WiFi
2. **Landscape Rotation**: Prueba en ambas orientaciones
3. **Screen Reader Test**: Si accesibilidad es crítica
4. **Network Throttling**: Simula 4G/3G en DevTools
5. **Report Issues**: Documenta en bug tracker con:
   - Device model
   - Viewport size
   - Steps to reproduce
   - Screenshots/videos

---

## Support

**Para reportar issues**:
```
Ir a: c:\xampp\htdocs\CEOGestion\RESPONSIVE_DESIGN_DOCUMENTATION.md
O: c:\xampp\htdocs\CEOGestion\CHANGELOG_RESPONSIVE_V2.md

Para errores técnicos:
1. Abre DevTools (F12)
2. Copia console errors
3. Documenta en ISSUES.md o reporta al desarrollador
```

---

**Happy Testing! 🚀**  
**Última Actualización**: 2026-04-28  
**Versión**: v2.0 Responsive Complete
