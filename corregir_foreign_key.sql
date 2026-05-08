-- ============================================================================
-- CORRECCIÓN: Foreign Key Incompatible - CEOGestion
-- Ejecutar en phpMyAdmin para fijar el error de constraint
-- ============================================================================

-- PASO 1: Eliminar las tablas que tienen el error
DROP TABLE IF EXISTS empresa_theme_settings;
DROP TABLE IF EXISTS themes;

-- PASO 2: Recrear tabla de temas
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

-- PASO 3: Recrear tabla de configuración de temas por empresa (IMPORTANTE: usar mismo tipo que empresas.id)
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

-- PASO 4: Insertar 5 temas predefinidos
INSERT INTO themes (name, label, description, color_primary, color_secondary, color_accent, bg_dark, bg_light, is_default, is_active) VALUES
('corporate-blue', 'Corporativo Azul', 'Professional blue theme for banking and corporate environments', '#0066CC', '#F5F5F5', '#00AA88', '#0D2A54', '#FFFFFF', TRUE, TRUE),
('elegant-black', 'Elegante Negro', 'Luxury black theme with gold accents', '#1A1A1A', '#EFEFEF', '#FFD700', '#0F0F0F', '#FAFAFA', FALSE, TRUE),
('modern-green', 'Moderno Verde', 'Fresh green theme for tech and startups', '#10B981', '#F0FDF4', '#8B5CF6', '#065F46', '#ECFDF5', FALSE, TRUE),
('tech-dark', 'Tech Oscuro', 'Dark cyberpunk theme for tech companies', '#0F172A', '#1E293B', '#06B6D4', '#020617', '#F1F5F9', FALSE, TRUE),
('warm-orange', 'Cálido Naranja', 'Warm orange theme for dynamic brands', '#EA580C', '#FEF3C7', '#F59E0B', '#78350F', '#FFFBEB', FALSE, TRUE);

-- PASO 5: Crear configuración de tema para cada empresa
INSERT INTO empresa_theme_settings (empresa_id, theme_id, is_dark_mode_default, allow_theme_toggle, show_ceo_logo)
SELECT id, 1, FALSE, TRUE, TRUE FROM empresas LIMIT 1;

-- PASO 6: Verificación
SELECT 'Temas creados:' as status, COUNT(*) as total FROM themes;
SELECT 'Configuración de empresas:' as status, COUNT(*) as total FROM empresa_theme_settings;
SELECT 'Verificación exitosa - Las tablas están creadas correctamente' as mensaje;
