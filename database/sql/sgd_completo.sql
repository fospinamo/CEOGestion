-- ============================================================
-- SISTEMA DE GESTION DOCUMENTAL (SGD) - CEOGestion
-- Modelo de datos completo segun normativa AGN Colombia
-- Base de datos: MySQL 8.0+
-- ============================================================

-- ============================================================
-- PARTE 1: DDL - CREACION DE TABLAS
-- ============================================================

-- TABLA 1: EMPRESAS (se reutiliza la tabla existente del sistema)
-- Campos: id, nombre, nit, digito_verificacion, telefono, email,
--          logo, descripcion, direccion, ciudad, estado, etc.

-- TABLA 2: DEPENDENCIAS
CREATE TABLE IF NOT EXISTS dependencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre del area u oficina',
    codigo VARCHAR(20) NOT NULL UNIQUE COMMENT 'Codigo interno de la dependencia',
    empresa_id BIGINT UNSIGNED NOT NULL,
    dependencia_padre_id BIGINT UNSIGNED NULL COMMENT 'Dependencia superior (NULL si es principal)',
    responsable VARCHAR(150) NULL COMMENT 'Nombre del jefe de area',
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_dependencias_estado (estado),
    INDEX idx_dependencias_empresa (empresa_id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (dependencia_padre_id) REFERENCES dependencias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 3: SERIES DOCUMENTALES
CREATE TABLE IF NOT EXISTS series_documentales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre de la serie documental',
    codigo VARCHAR(20) NOT NULL UNIQUE COMMENT 'Codigo de la serie',
    descripcion TEXT NULL COMMENT 'Descripcion de la funcion que genera documentos',
    dependencia_id BIGINT UNSIGNED NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_series_estado (estado),
    INDEX idx_series_dependencia (dependencia_id),
    FOREIGN KEY (dependencia_id) REFERENCES dependencias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 4: SUBSERIES DOCUMENTALES
CREATE TABLE IF NOT EXISTS subseries_documentales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre de la subserie',
    codigo VARCHAR(30) NOT NULL UNIQUE COMMENT 'Codigo de la subserie',
    serie_id BIGINT UNSIGNED NOT NULL,
    descripcion TEXT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_subseries_estado (estado),
    INDEX idx_subseries_serie (serie_id),
    FOREIGN KEY (serie_id) REFERENCES series_documentales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 5: TIPOS DOCUMENTALES
CREATE TABLE IF NOT EXISTS tipos_documentales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre del tipo documental',
    codigo VARCHAR(40) NOT NULL UNIQUE COMMENT 'Codigo del tipo documental',
    subserie_id BIGINT UNSIGNED NULL,
    serie_id BIGINT UNSIGNED NOT NULL,
    descripcion TEXT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_tipos_estado (estado),
    INDEX idx_tipos_serie (serie_id),
    INDEX idx_tipos_subserie (subserie_id),
    FOREIGN KEY (subserie_id) REFERENCES subseries_documentales(id) ON DELETE SET NULL,
    FOREIGN KEY (serie_id) REFERENCES series_documentales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 6: UBICACIONES FISICAS
CREATE TABLE IF NOT EXISTS ubicaciones_fisicas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    empresa_id BIGINT UNSIGNED NOT NULL,
    dependencia_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre del espacio de archivo',
    codigo VARCHAR(30) NOT NULL UNIQUE COMMENT 'Codigo del espacio',
    tipo_archivo ENUM('Gestion','Central','Historico') NOT NULL COMMENT 'Tipo de archivo',
    estanteria VARCHAR(50) NULL,
    fila VARCHAR(10) NULL,
    nivel VARCHAR(10) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_ubicaciones_estado (estado),
    INDEX idx_ubicaciones_empresa (empresa_id),
    INDEX idx_ubicaciones_tipo (tipo_archivo),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (dependencia_id) REFERENCES dependencias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 7: TRD DETALLE (se agrega a la tabla existente trd_detalle)
-- Campos FK nuevos: dependencia_id, serie_id, subserie_id,
--   tipo_documental_id, ubicacion_gestion_id, ubicacion_central_id
-- ALTER TABLE trd_detalle ADD COLUMN dependencia_id BIGINT UNSIGNED NULL AFTER tabla_retencion_documental_id;
-- ALTER TABLE trd_detalle ADD COLUMN serie_id BIGINT UNSIGNED NULL AFTER dependencia_id;
-- ALTER TABLE trd_detalle ADD COLUMN subserie_id BIGINT UNSIGNED NULL AFTER serie_id;
-- ALTER TABLE trd_detalle ADD COLUMN tipo_documental_id BIGINT UNSIGNED NULL AFTER subserie_id;
-- ALTER TABLE trd_detalle ADD COLUMN ubicacion_gestion_id BIGINT UNSIGNED NULL AFTER tipo_documental_id;
-- ALTER TABLE trd_detalle ADD COLUMN ubicacion_central_id BIGINT UNSIGNED NULL AFTER ubicacion_gestion_id;

-- TABLA 8: DOCUMENTOS (Expedientes/Archivos)
CREATE TABLE IF NOT EXISTS sgd_documentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    trd_detalle_id BIGINT UNSIGNED NOT NULL,
    empresa_id BIGINT UNSIGNED NOT NULL,
    numero_documento VARCHAR(50) NOT NULL COMMENT 'Numero de radicado o folio',
    fecha_documento DATE NOT NULL COMMENT 'Fecha de creacion del documento',
    ubicacion_actual_id BIGINT UNSIGNED NULL,
    dependencia_creadora_id BIGINT UNSIGNED NOT NULL,
    dependencia_destino_id BIGINT UNSIGNED NULL,
    descripcion TEXT NULL COMMENT 'Asunto o contenido del documento',
    volumen VARCHAR(30) NULL COMMENT 'Cantidad de folios',
    soporte ENUM('Fisico','Digital','Ambos') NOT NULL DEFAULT 'Fisico',
    fecha_ingreso_archivo DATE NULL,
    estado_documento ENUM('Activo','Transferido','Eliminado') NOT NULL DEFAULT 'Activo',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_documentos_estado (estado_documento),
    INDEX idx_documentos_empresa (empresa_id),
    INDEX idx_documentos_fecha (fecha_documento),
    INDEX idx_documentos_trd (trd_detalle_id),
    UNIQUE KEY uk_documentos_numero (numero_documento, empresa_id),
    FOREIGN KEY (trd_detalle_id) REFERENCES trd_detalle(id) ON DELETE CASCADE,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (ubicacion_actual_id) REFERENCES ubicaciones_fisicas(id) ON DELETE SET NULL,
    FOREIGN KEY (dependencia_creadora_id) REFERENCES dependencias(id) ON DELETE CASCADE,
    FOREIGN KEY (dependencia_destino_id) REFERENCES dependencias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 9: USUARIOS (se reutiliza la tabla users del sistema)

-- TABLA 10: PERMISOS DE ACCESO
CREATE TABLE IF NOT EXISTS permisos_acceso (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    serie_id BIGINT UNSIGNED NULL,
    dependencia_id BIGINT UNSIGNED NULL,
    tipo_acceso ENUM('Lectura','Escritura','Eliminacion','Administracion') NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL COMMENT 'NULL = indefinido',
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_permisos_estado (estado),
    INDEX idx_permisos_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (serie_id) REFERENCES series_documentales(id) ON DELETE SET NULL,
    FOREIGN KEY (dependencia_id) REFERENCES dependencias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 11: TRANSFERENCIAS
CREATE TABLE IF NOT EXISTS transferencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_transferencia DATE NOT NULL,
    documento_id BIGINT UNSIGNED NOT NULL,
    ubicacion_origen_id BIGINT UNSIGNED NOT NULL,
    ubicacion_destino_id BIGINT UNSIGNED NOT NULL,
    tipo_transferencia ENUM('Primaria','Secundaria') NOT NULL COMMENT 'Primaria: Gestion->Central, Secundaria: Central->Historico',
    usuario_responsable_id BIGINT UNSIGNED NOT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_transferencias_fecha (fecha_transferencia),
    INDEX idx_transferencias_tipo (tipo_transferencia),
    FOREIGN KEY (documento_id) REFERENCES sgd_documentos(id) ON DELETE CASCADE,
    FOREIGN KEY (ubicacion_origen_id) REFERENCES ubicaciones_fisicas(id) ON DELETE CASCADE,
    FOREIGN KEY (ubicacion_destino_id) REFERENCES ubicaciones_fisicas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_responsable_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- PARTE 2: INSERTS DE EJEMPLO
-- ============================================================

-- EMPRESAS (asumiendo que ya existe una empresa con id=1)
-- INSERT INTO empresas (nombre, nit, digito_verificacion, direccion, ciudad, telefono, estado)
-- VALUES ('Sede Principal - Bogota', '900123456', '7', 'Calle 100 # 15-80', 'Bogota D.C.', '601-5551234', 1);

-- DEPENDENCIAS
INSERT INTO dependencias (nombre, codigo, empresa_id, dependencia_padre_id, responsable, estado, created_at, updated_at) VALUES
('Gerencia General', 'GER-01', 1, NULL, 'Carlos Martinez', 1, NOW(), NOW()),
('Contabilidad', 'CON-01', 1, NULL, 'Maria Perez', 1, NOW(), NOW()),
('Talento Humano', 'TH-01', 1, NULL, 'Ana Rodriguez', 1, NOW(), NOW()),
('Oficina Juridica', 'JUR-01', 1, NULL, 'Luis Fernandez', 1, NOW(), NOW()),
('Pagaduria', 'PAG-01', 1, 2, 'Sandra Lopez', 1, NOW(), NOW()),
('Cartera', 'CAR-01', 1, 2, 'Pedro Gutierrez', 1, NOW(), NOW()),
('Planeacion', 'PLA-01', 1, NULL, 'Diana Torres', 1, NOW(), NOW()),
('Tecnologia', 'TEC-01', 1, NULL, 'Andres Moreno', 1, NOW(), NOW());

-- SERIES DOCUMENTALES
INSERT INTO series_documentales (nombre, codigo, descripcion, dependencia_id, estado, created_at, updated_at) VALUES
('Comprobantes de Egreso', 'CE-001', 'Documentos que soportan salidas de dinero de la empresa', 2, 1, NOW(), NOW()),
('Estados Financieros', 'EF-001', 'Informes financieros periodicos de la empresa', 2, 1, NOW(), NOW()),
('Vinculacion de Personal', 'VP-001', 'Documentos relacionados con contratacion de personal', 3, 1, NOW(), NOW()),
('Nomina', 'NOM-001', 'Liquidaciones y pagos de nomina', 3, 1, NOW(), NOW()),
('Actos Administrativos', 'AA-001', 'Resoluciones y decretos internos', 4, 1, NOW(), NOW()),
('Procesos Judiciales', 'PJ-001', 'Documentacion de procesos legales', 4, 1, NOW(), NOW()),
('Organos de Direccion', 'OD-001', 'Actas y documentos de junta directiva', 1, 1, NOW(), NOW()),
('Contratos', 'CON-001', 'Documentos contractuales de la empresa', 4, 1, NOW(), NOW()),
('Correspondencia', 'COR-001', 'Cartas entrantes y salientes', 7, 1, NOW(), NOW()),
('Seguridad Informatica', 'SI-001', 'Politicas y registros de seguridad digital', 8, 1, NOW(), NOW());

-- SUBSERIES DOCUMENTALES
INSERT INTO subseries_documentales (nombre, codigo, serie_id, descripcion, estado, created_at, updated_at) VALUES
('Pagos a Proveedores', 'CE-001-01', 1, 'Egresos por compras de bienes y servicios', 1, NOW(), NOW()),
('Pagos de Nomina', 'CE-001-02', 1, 'Egresos por pago de salarios y prestaciones', 1, NOW(), NOW()),
('Gastos Menores', 'CE-001-03', 1, 'Egresos por gastos operativos menores', 1, NOW(), NOW()),
('Contratos Termino Indefinido', 'VP-001-01', 3, 'Contratos de trabajo a termino indefinido', 1, NOW(), NOW()),
('Contratos Termino Fijo', 'VP-001-02', 3, 'Contratos de trabajo a termino fijo', 1, NOW(), NOW()),
('Liquidaciones de Nomina', 'NOM-001-01', 4, 'Liquidaciones mensuales de nomina', 1, NOW(), NOW()),
('Prestaciones Sociales', 'NOM-001-02', 4, 'Prima, cesantias, vacaciones', 1, NOW(), NOW()),
('Actas de Junta', 'OD-001-01', 7, 'Actas de reuniones de junta directiva', 1, NOW(), NOW()),
('Contratos de Prestacion de Servicios', 'CON-001-01', 8, 'Contratos con terceros para servicios', 1, NOW(), NOW()),
('Politicas de Seguridad', 'SI-001-01', 10, 'Politicas y procedimientos de seguridad', 1, NOW(), NOW());

-- TIPOS DOCUMENTALES
INSERT INTO tipos_documentales (nombre, codigo, subserie_id, serie_id, descripcion, estado, created_at, updated_at) VALUES
('Factura de Compra', 'CE-001-01-FAC', 1, 1, 'Documento que soporta la compra de bienes', 1, NOW(), NOW()),
('Recibo de Caja Menor', 'CE-001-03-RCM', 3, 1, 'Comprobante de egreso por gastos menores', 1, NOW(), NOW()),
('Comprobante de Pago de Salarios', 'CE-001-02-CPS', 2, 1, 'Soporte de pago de nomina', 1, NOW(), NOW()),
('Contrato de Trabajo Indefinido', 'VP-001-01-CTI', 4, 3, 'Contrato laboral a termino indefinido', 1, NOW(), NOW()),
('Hoja de Vida', 'VP-001-HV', NULL, 3, 'Hoja de vida del aspirante o empleado', 1, NOW(), NOW()),
('Balance General', 'EF-001-BG', NULL, 2, 'Estado financiero de la empresa', 1, NOW(), NOW()),
('Acta de Junta Directiva', 'OD-001-AJD', 8, 7, 'Acta oficial de reunion de junta', 1, NOW(), NOW()),
('Contrato de Prestacion de Servicios', 'CON-001-01-CPS', 9, 8, 'Contrato con terceros', 1, NOW(), NOW()),
('Politica de Seguridad Informatica', 'SI-001-01-PSI', 10, 10, 'Politica institucional de seguridad', 1, NOW(), NOW()),
('Certificado Laboral', 'VP-001-CL', NULL, 3, 'Certificacion de tiempo laborado', 1, NOW(), NOW());

-- UBICACIONES FISICAS
INSERT INTO ubicaciones_fisicas (empresa_id, dependencia_id, nombre, codigo, tipo_archivo, estanteria, fila, nivel, estado, created_at, updated_at) VALUES
(1, 2, 'Archivo Contable Gestion', 'ARC-CON-01', 'Gestion', 'E-15', 'Fila 3', 'Nivel 2', 1, NOW(), NOW()),
(1, 2, 'Archivo Contable Central', 'ARC-CON-02', 'Central', 'E-16', 'Fila 1', 'Nivel 1', 1, NOW(), NOW()),
(1, 3, 'Archivo TH Gestion', 'ARC-TH-01', 'Gestion', 'E-12', 'Fila 2', 'Nivel 1', 1, NOW(), NOW()),
(1, 3, 'Archivo TH Central', 'ARC-TH-02', 'Central', 'E-13', 'Fila 1', 'Nivel 1', 1, NOW(), NOW()),
(1, NULL, 'Archivo Historico General', 'ARC-HIS-01', 'Historico', 'E-20', 'Fila 1', 'Nivel 1', 1, NOW(), NOW()),
(1, 4, 'Archivo Juridico Gestion', 'ARC-JUR-01', 'Gestion', 'E-14', 'Fila 2', 'Nivel 1', 1, NOW(), NOW()),
(1, 4, 'Archivo Juridico Central', 'ARC-JUR-02', 'Central', 'E-17', 'Fila 1', 'Nivel 1', 1, NOW(), NOW()),
(1, 1, 'Archivo Gerencia Gestion', 'ARC-GER-01', 'Gestion', 'E-11', 'Fila 1', 'Nivel 1', 1, NOW(), NOW());

-- TRD DETALLE (ejemplo de registros con FKs)
INSERT INTO trd_detalle (tabla_retencion_documental_id, dependencia, serie, subserie, tipo_documental, archivo_gestion_tiempo, archivo_central_tiempo, disposicion_final, observaciones, orden, dependencia_id, serie_id, subserie_id, tipo_documental_id, ubicacion_gestion_id, ubicacion_central_id, created_at, updated_at) VALUES
(1, 'Contabilidad', 'Comprobantes de Egreso', 'Pagos a Proveedores', 'Factura de Compra', '5 anios', '10 anios', 'EL', 'Ley 50/1990, Art. 10 - Facturas superiores a 100 SMMLV', 1, 2, 1, 1, 1, 1, 2, NOW(), NOW()),
(1, 'Contabilidad', 'Comprobantes de Egreso', 'Pagos de Nomina', 'Comprobante de Pago de Salarios', '5 anios', '10 anios', 'EL', 'Codigo Sustantivo del Trabajo', 2, 2, 1, 2, 3, 1, 2, NOW(), NOW()),
(1, 'Contabilidad', 'Estados Financieros', NULL, 'Balance General', '5 anios', '10 anios', 'CP', 'Valor patrimonial e historico - Conservacion Permanente', 3, 2, 2, NULL, 6, 1, 2, NOW(), NOW()),
(1, 'Talento Humano', 'Vinculacion de Personal', 'Contratos Termino Indefinido', 'Contrato de Trabajo Indefinido', 'Tiempo vinculacion + 5 anios', '5 anios', 'EL', 'Ley 50/1990, Art. 45', 4, 3, 3, 4, 4, 3, 4, NOW(), NOW()),
(1, 'Talento Humano', 'Vinculacion de Personal', NULL, 'Hoja de Vida', 'Tiempo vinculacion + 3 anios', '10 anios', 'D', 'Decreto 1083/2015 - Digitalizar antes de eliminar fisico', 5, 3, 3, NULL, 5, 3, 4, NOW(), NOW()),
(1, 'Talento Humano', 'Nomina', 'Liquidaciones de Nomina', 'Liquidacion de Nomina', '5 anios', '10 anios', 'EL', 'Codigo Sustantivo del Trabajo', 6, 3, 4, 6, NULL, 3, 4, NOW(), NOW()),
(1, 'Oficina Juridica', 'Actos Administrativos', NULL, 'Resolucion', '10 anios', 'Conservacion Total', 'CP', 'Ley 594/2000 - Acto administrativo con valor juridico', 7, 4, 5, NULL, NULL, 6, 7, NOW(), NOW()),
(1, 'Gerencia General', 'Organos de Direccion', 'Actas de Junta', 'Acta de Junta Directiva', '10 anios', 'Conservacion Total', 'CP', 'Acta oficial con valor patrimonial', 8, 1, 7, 8, 7, 8, NULL, NOW(), NOW());

-- DOCUMENTOS (ejemplos)
INSERT INTO sgd_documentos (trd_detalle_id, empresa_id, numero_documento, fecha_documento, ubicacion_actual_id, dependencia_creadora_id, dependencia_destino_id, descripcion, volumen, soporte, fecha_ingreso_archivo, estado_documento, created_at, updated_at) VALUES
(1, 1, 'RAD-2026-0001', '2026-01-15', 1, 2, NULL, 'Factura de compra de computadores Dell', '3 folios', 'Fisico', '2026-01-16', 'Activo', NOW(), NOW()),
(1, 1, 'RAD-2026-0002', '2026-02-20', 1, 2, NULL, 'Factura de compra de muebles oficina', '2 folios', 'Fisico', '2026-02-21', 'Activo', NOW(), NOW()),
(3, 1, 'RAD-2026-0003', '2026-03-10', 2, 2, NULL, 'Balance General Primer Trimestre 2026', '15 folios', 'Digital', '2026-03-11', 'Activo', NOW(), NOW()),
(4, 1, 'RAD-2026-0004', '2026-01-05', 3, 3, NULL, 'Contrato de trabajo - Juan Perez (Indefinido)', '8 folios', 'Fisico', '2026-01-05', 'Activo', NOW(), NOW()),
(5, 1, 'RAD-2026-0005', '2026-02-01', 3, 3, NULL, 'Hoja de vida - Maria Garcia', '5 folios', 'Ambos', '2026-02-01', 'Activo', NOW(), NOW()),
(7, 1, 'RES-2026-001', '2026-04-15', 6, 4, NULL, 'Resolucion de Contratacion Personal', '4 folios', 'Fisico', '2026-04-15', 'Activo', NOW(), NOW()),
(8, 1, 'ACT-2026-001', '2026-03-20', 8, 1, NULL, 'Acta de Junta Directiva Q1 2026', '10 folios', 'Digital', '2026-03-21', 'Activo', NOW(), NOW());

-- PERMISOS DE ACCESO
INSERT INTO permisos_acceso (user_id, serie_id, dependencia_id, tipo_acceso, fecha_inicio, fecha_fin, estado, created_at, updated_at) VALUES
(1, 1, 2, 'Administracion', '2026-01-01', NULL, 1, NOW(), NOW()),
(1, 3, 3, 'Administracion', '2026-01-01', NULL, 1, NOW(), NOW()),
(2, 1, 2, 'Escritura', '2026-01-01', NULL, 1, NOW(), NOW()),
(2, 2, 2, 'Lectura', '2026-01-01', NULL, 1, NOW(), NOW()),
(3, 3, 3, 'Escritura', '2026-01-01', NULL, 1, NOW(), NOW()),
(3, 4, 3, 'Lectura', '2026-01-01', NULL, 1, NOW(), NOW());

-- TRANSFERENCIAS (ejemplo)
INSERT INTO transferencias (fecha_transferencia, documento_id, ubicacion_origen_id, ubicacion_destino_id, tipo_transferencia, usuario_responsable_id, observaciones, created_at, updated_at) VALUES
('2031-08-24', 1, 1, 2, 'Primaria', 1, 'Transferencia masiva de facturas 2026', NOW(), NOW()),
('2036-08-24', 4, 3, 4, 'Primaria', 1, 'Transferencia de contratos de trabajo 2026', NOW(), NOW());


-- ============================================================
-- PARTE 3: VISTA CONSOLIDADA DE LA TRD
-- ============================================================

CREATE OR REPLACE VIEW vw_trd_completa AS
SELECT
    e.nombre AS empresa,
    e.nit,
    dep.codigo AS codigo_dependencia,
    dep.nombre AS dependencia,
    dep_padre.nombre AS dependencia_superior,
    sd.codigo AS codigo_serie,
    sd.nombre AS serie,
    ssd.codigo AS codigo_subserie,
    ssd.nombre AS subserie,
    td.codigo AS codigo_tipo_documental,
    td.nombre AS tipo_documental,
    td.descripcion AS tipo_descripcion,
    trd_det.archivo_gestion_tiempo,
    trd_det.archivo_central_tiempo,
    CASE trd_det.disposicion_final
        WHEN 'CP' THEN 'Conservacion Permanente'
        WHEN 'EL' THEN 'Eliminacion'
        WHEN 'D' THEN 'Digitalizacion'
        ELSE trd_det.disposicion_final
    END AS disposicion_final,
    trd_det.observaciones AS normativa_aplicable,
    ug.codigo AS ubicacion_gestion_codigo,
    ug.nombre AS ubicacion_gestion_nombre,
    ug.estanteria AS ubicacion_gestion_estanteria,
    uc.codigo AS ubicacion_central_codigo,
    uc.nombre AS ubicacion_central_nombre,
    uc.estanteria AS ubicacion_central_estanteria,
    trd.nombre AS nombre_trd,
    trd.consecutivo AS numero_trd,
    trd.fecha_creacion AS fecha_creacion_trd,
    CASE
        WHEN trd.convalidado_agn = 1 THEN 'Convalidado'
        WHEN trd.aprobado_comite = 1 THEN 'Aprobado'
        ELSE 'En Revision'
    END AS estado_trd,
    trd_det.orden
FROM trd_detalle trd_det
INNER JOIN tablas_retencion_documental trd ON trd.id = trd_det.tabla_retencion_documental_id
INNER JOIN empresas e ON e.id = trd.empresa_id
LEFT JOIN dependencias dep ON dep.id = trd_det.dependencia_id
LEFT JOIN dependencias dep_padre ON dep_padre.id = dep.dependencia_padre_id
LEFT JOIN series_documentales sd ON sd.id = trd_det.serie_id
LEFT JOIN subseries_documentales ssd ON ssd.id = trd_det.subserie_id
LEFT JOIN tipos_documentales td ON td.id = trd_det.tipo_documental_id
LEFT JOIN ubicaciones_fisicas ug ON ug.id = trd_det.ubicacion_gestion_id
LEFT JOIN ubicaciones_fisicas uc ON uc.id = trd_det.ubicacion_central_id
WHERE trd_det.estado IS NOT FALSE
ORDER BY e.nombre, dep.nombre, sd.nombre, trd_det.orden;


-- ============================================================
-- PARTE 4: PROCEDIMIENTO ALMACENADO - FECHA TRANSFERENCIA
-- ============================================================

DELIMITER //

CREATE PROCEDURE sp_calcular_fechas_transferencia(
    IN p_documento_id BIGINT UNSIGNED
)
BEGIN
    DECLARE v_fecha_doc DATE;
    DECLARE v_gestion_anios INT;
    DECLARE v_central_anios INT;
    DECLARE v_fecha_transferencia_gestion DATE;
    DECLARE v_fecha_transferencia_historico DATE;

    -- Obtener datos del documento y su TRD
    SELECT
        d.fecha_documento,
        CAST(REPLACE(REPLACE(tdet.archivo_gestion_tiempo, ' anios', ''), ' ano', '') AS UNSIGNED),
        CAST(REPLACE(REPLACE(tdet.archivo_central_tiempo, ' anios', ''), ' ano', '') AS UNSIGNED)
    INTO v_fecha_doc, v_gestion_anios, v_central_anios
    FROM sgd_documentos d
    INNER JOIN trd_detalle tdet ON tdet.id = d.trd_detalle_id
    WHERE d.id = p_documento_id;

    -- Calcular fechas de transferencia
    IF v_fecha_doc IS NOT NULL THEN
        SET v_fecha_transferencia_gestion = DATE_ADD(v_fecha_doc, INTERVAL v_gestion_anios YEAR);
        SET v_fecha_transferencia_historico = DATE_ADD(v_fecha_transferencia_gestion, INTERVAL v_central_anios YEAR);

        -- Retornar resultados
        SELECT
            p_documento_id AS documento_id,
            v_fecha_doc AS fecha_documento,
            v_gestion_anios AS anios_gestion,
            v_central_anios AS anios_central,
            v_fecha_transferencia_gestion AS fecha_transferencia_gestion,
            v_fecha_transferencia_historico AS fecha_transferencia_historico,
            CASE
                WHEN CURDATE() >= v_fecha_transferencia_gestion THEN 'DEBE TRANSFERIRSE A ARCHIVO CENTRAL'
                WHEN CURDATE() >= DATE_SUB(v_fecha_transferencia_gestion, INTERVAL 6 MONTH) THEN 'PROXIMO A TRANSFERIR (6 meses)'
                ELSE 'EN ARCHIVO DE GESTION'
            END AS estado_actual,
            DATEDIFF(v_fecha_transferencia_gestion, CURDATE()) AS dias_para_transferencia;
    ELSE
        SELECT 'ERROR: Documento no encontrado' AS mensaje;
    END IF;
END //

DELIMITER ;


-- ============================================================
-- PARTE 5: REPORTE TRD COMPLETA (FORMATO TABULAR)
-- ============================================================

-- Reporte: TRD completa agrupada por dependencia
SELECT
    vw.empresa,
    vw.dependencia,
    vw.codigo_serie AS cod_serie,
    vw.serie,
    vw.codigo_subserie AS cod_subserie,
    COALESCE(vw.subserie, '-') AS subserie,
    vw.tipo_documental,
    vw.archivo_gestion_tiempo AS retencion_gestion,
    vw.archivo_central_tiempo AS retencion_central,
    vw.disposicion_final,
    vw.ubicacion_gestion_nombre AS archivo_gestion,
    vw.ubicacion_central_nombre AS archivo_central,
    vw.normativa_aplicable,
    vw.estado_trd
FROM vw_trd_completa vw
ORDER BY vw.dependencia, vw.serie, vw.subserie, vw.tipo_documental;


-- ============================================================
-- PARTE 6: CONSULTAS UTILES
-- ============================================================

-- Documentos proximos a transferir a Archivo Central (proximo semestre)
SELECT
    d.numero_documento,
    d.fecha_documento,
    d.descripcion,
    dep.nombre AS dependencia,
    td.nombre AS tipo_documental,
    tdet.archivo_gestion_tiempo,
    DATE_ADD(d.fecha_documento, INTERVAL CAST(REPLACE(REPLACE(tdet.archivo_gestion_tiempo, ' anios', ''), ' ano', '') AS UNSIGNED) YEAR) AS fecha_limite_gestion,
    DATEDIFF(
        DATE_ADD(d.fecha_documento, INTERVAL CAST(REPLACE(REPLACE(tdet.archivo_gestion_tiempo, ' anios', ''), ' ano', '') AS UNSIGNED) YEAR),
        CURDATE()
    ) AS dias_restantes
FROM sgd_documentos d
INNER JOIN trd_detalle tdet ON tdet.id = d.trd_detalle_id
INNER JOIN dependencias dep ON dep.id = d.dependencia_creadora_id
LEFT JOIN tipos_documentales td ON td.id = tdet.tipo_documental_id
WHERE d.estado_documento = 'Activo'
AND DATE_ADD(d.fecha_documento, INTERVAL CAST(REPLACE(REPLACE(tdet.archivo_gestion_tiempo, ' anios', ''), ' ano', '') AS UNSIGNED) YEAR) <= DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
ORDER BY dias_restantes ASC;


-- Conteo de documentos por dependencia y tipo de disposicion
SELECT
    dep.nombre AS dependencia,
    COUNT(CASE WHEN tdet.disposicion_final = 'CP' THEN 1 END) AS conservacion_permanente,
    COUNT(CASE WHEN tdet.disposicion_final = 'EL' THEN 1 END) AS eliminacion,
    COUNT(CASE WHEN tdet.disposicion_final = 'D' THEN 1 END) AS digitalizacion,
    COUNT(*) AS total
FROM sgd_documentos d
INNER JOIN trd_detalle tdet ON tdet.id = d.trd_detalle_id
INNER JOIN dependencias dep ON dep.id = d.dependencia_creadora_id
WHERE d.estado_documento = 'Activo'
GROUP BY dep.nombre
ORDER BY total DESC;


-- Ejemplo de uso del procedimiento almacenado
-- CALL sp_calcular_fechas_transferencia(1);
