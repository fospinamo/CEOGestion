# 🔧 CORRECCIÓN - Foreign Key Incompatible

**Problema:** Error #3780 - Tipos de datos incompatibles en la Foreign Key  
**Causa:** La tabla `empresas` usa `BIGINT UNSIGNED` pero las migraciones usaban `INT`  
**Solución:** Recrear las tablas con los tipos correctos

---

## ✅ SOLUCIÓN RÁPIDA (5 minutos)

### Paso 1: Descargar el archivo SQL correcto

He preparado el archivo: `corregir_foreign_key.sql`

Está en: `c:\xampp\htdocs\CEOGestion\corregir_foreign_key.sql`

### Paso 2: Acceder a phpMyAdmin en cPanel

1. En cPanel, busca **phpMyAdmin**
2. Selecciona tu base de datos
3. Haz click en **SQL** (en la parte superior)

### Paso 3: Copiar y ejecutar el SQL

1. Abre el archivo `corregir_foreign_key.sql`
2. Copia TODO el contenido
3. Pega en phpMyAdmin en la pestaña **SQL**
4. Haz click en **Go** (o **Execute**)

### Paso 4: Verificar que funcionó

Deberías ver mensajes como:
```
Temas creados: 5
Configuración de empresas: 1
Verificación exitosa
```

---

## 📝 QUÉ HACE ESTE SQL

1. ✓ Elimina las tablas con el error
2. ✓ Recrea `themes` con tipos correctos (`BIGINT UNSIGNED`)
3. ✓ Recrea `empresa_theme_settings` con tipos compatibles
4. ✓ Inserta los 5 temas predefinidos
5. ✓ Crea la configuración por empresa

---

## ⚠️ SI PREFIERES HACERLO MANUAL

### Opción alternativa: Copiar este SQL directo

Si no quieres descargar el archivo, copia esto en phpMyAdmin SQL:

```sql
-- Eliminar tablas con error
DROP TABLE IF EXISTS empresa_theme_settings;
DROP TABLE IF EXISTS themes;

-- Crear tabla de temas con tipo correcto
CREATE TABLE themes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    label VARCHAR(255) NOT NULL,
    description TEXT,
    color_primary VARCHAR(7) DEFAULT '#0066CC',
    color_secondary VARCHAR(7) DEFAULT '#F5F5F5',
    color_accent VARCHAR(7) DEFAULT '#00AA88',
    color_text VARCHAR(7) DEFAULT '#1A1A1A',
    color_text_light VARCHAR(7) DEFAULT '#FFFFFF',
    bg_dark VARCHAR(7) DEFAULT '#0D2A54',
    bg_light VARCHAR(7) DEFAULT '#FFFFFF',
    is_default BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de configuración con tipo correcto
CREATE TABLE empresa_theme_settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    empresa_id BIGINT UNSIGNED NOT NULL,
    theme_id BIGINT UNSIGNED,
    color_primary VARCHAR(7),
    color_secondary VARCHAR(7),
    color_accent VARCHAR(7),
    color_text VARCHAR(7),
    color_text_light VARCHAR(7),
    is_dark_mode_default BOOLEAN DEFAULT FALSE,
    allow_theme_toggle BOOLEAN DEFAULT TRUE,
    show_ceo_logo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_empresa_id (empresa_id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar 5 temas
INSERT INTO themes (name, label, description, color_primary, color_secondary, color_accent, bg_dark, bg_light, is_default, is_active) VALUES
('corporate-blue', 'Corporativo Azul', 'Professional blue theme for banking and corporate environments', '#0066CC', '#F5F5F5', '#00AA88', '#0D2A54', '#FFFFFF', TRUE, TRUE),
('elegant-black', 'Elegante Negro', 'Luxury black theme with gold accents', '#1A1A1A', '#EFEFEF', '#FFD700', '#0F0F0F', '#FAFAFA', FALSE, TRUE),
('modern-green', 'Moderno Verde', 'Fresh green theme for tech and startups', '#10B981', '#F0FDF4', '#8B5CF6', '#065F46', '#ECFDF5', FALSE, TRUE),
('tech-dark', 'Tech Oscuro', 'Dark cyberpunk theme for tech companies', '#0F172A', '#1E293B', '#06B6D4', '#020617', '#F1F5F9', FALSE, TRUE),
('warm-orange', 'Cálido Naranja', 'Warm orange theme for dynamic brands', '#EA580C', '#FEF3C7', '#F59E0B', '#78350F', '#FFFBEB', FALSE, TRUE);

-- Crear configuración por empresa
INSERT INTO empresa_theme_settings (empresa_id, theme_id, is_dark_mode_default, allow_theme_toggle, show_ceo_logo)
SELECT id, 1, FALSE, TRUE, TRUE FROM empresas LIMIT 1;
```

---

## ✅ VERIFICACIÓN FINAL

Después de ejecutar el SQL, accede a:

```
https://gestion.simotec.com.co/CEOGestion/login
```

**Debe verse:**
- ✓ Logo empresa visible
- ✓ Sin errores
- ✓ Formulario login funciona

---

## 🆘 Si AÚNFALLA

1. **Asegúrate que:** 
   - La tabla `empresas` existe en la BD
   - Tienes acceso a phpMyAdmin
   - Copias TODO el SQL correctamente

2. **Intenta lo siguiente:**
   - Ejecuta solo el DROP: `DROP TABLE IF EXISTS empresa_theme_settings; DROP TABLE IF EXISTS themes;`
   - Luego ejecuta el CREATE de una en una

3. **Si sigue fallando:**
   - Copia el contenido del log: `/storage/logs/laravel.log`
   - Envía captura de pantalla del error exacto

---

**¿Ejecutaste el SQL? ¿Te dio error o funcionó?**
