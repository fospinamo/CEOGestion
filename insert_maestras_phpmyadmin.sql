-- INSERTS PARA PHPYADMIN
-- Propósito: Llenar maestras sin Terminal cPanel
-- Uso: Copiar y pegar en phpMyAdmin > SQL
-- Fecha: 2026-05-06 15:21:26

SET FOREIGN_KEY_CHECKS=0;

-- ==========================================
-- Tabla: paises (1 registros)
-- ==========================================
DELETE FROM `paises`;
INSERT INTO `paises` (`id`, `codigo_dane`, `nombre`, `created_at`, `updated_at`) VALUES ('1', '170', 'Colombia', '2026-05-06 02:15:27', '2026-05-06 02:15:27');

-- ==========================================
-- Tabla: departamentos (32 registros)
-- ==========================================
DELETE FROM `departamentos`;
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('1', '05', 'Antioquia', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('2', '08', 'Atlántico', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('3', '11', 'Bogotá D.C.', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('4', '13', 'Bolívar', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('5', '15', 'Boyacá', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('6', '17', 'Caldas', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('7', '18', 'Caquetá', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('8', '19', 'Cauca', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('9', '20', 'Cesar', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('10', '23', 'Córdoba', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('11', '25', 'Cundinamarca', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('12', '27', 'Guaviare', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('13', '41', 'Huila', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('14', '44', 'La Guajira', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('15', '47', 'Magdalena', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('16', '50', 'Meta', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('17', '52', 'Nariño', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('18', '54', 'Norte de Santander', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('19', '63', 'Quindío', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('20', '66', 'Risaralda', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('21', '68', 'Santander', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('22', '70', 'Sucre', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('23', '73', 'Tolima', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('24', '76', 'Valle del Cauca', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('25', '81', 'Arauca', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('26', '85', 'Casanare', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('27', '86', 'Putumayo', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('28', '88', 'San Andrés y Providencia', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('29', '91', 'Amazonas', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('30', '94', 'Vichada', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('31', '95', 'Guainía', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `departamentos` (`id`, `codigo_dane`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES ('32', '97', 'Vaupés', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');

-- ==========================================
-- Tabla: municipios (36 registros)
-- ==========================================
DELETE FROM `municipios`;
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('1', '05001', 'Medellín', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('2', '05002', 'Abejorral', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('3', '05004', 'Abrigo', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('4', '05021', 'Belmira', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('5', '05030', 'Bello', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('6', '11001', 'Bogotá', '3', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('7', '25001', 'Agua de Dios', '11', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('8', '25019', 'Bojacá', '11', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('9', '25040', 'Chía', '11', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('10', '25175', 'Mosquera', '11', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('11', '25286', 'Soacha', '11', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('12', '76001', 'Alcalá', '24', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('13', '76020', 'Cali', '24', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('14', '76176', 'Palmira', '24', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('15', '76520', 'Tuluá', '24', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('16', '76834', 'Yumbo', '24', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('17', '08001', 'Baranoa', '2', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('18', '08078', 'Barranquilla', '2', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('19', '08141', 'Malambo', '2', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('20', '08296', 'Soledad', '2', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('21', '23001', 'Ayapel', '10', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('22', '23068', 'Lorica', '10', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('23', '23205', 'Montería', '10', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('24', '68001', 'Aguada', '21', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('25', '68264', 'Piedecuesta', '21', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('26', '68307', 'Puente Nacional', '21', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('27', '68547', 'Bucaramanga', '21', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('28', '19001', 'Almaguer', '8', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('29', '19075', 'Popayán', '8', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('30', '19136', 'Silvia', '8', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('31', '52001', 'Albán', '17', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('32', '52189', 'Pasto', '17', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('33', '52517', 'Tumaco', '17', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('34', '50001', 'Acacías', '16', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('35', '50290', 'Villavicencio', '16', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `municipios` (`id`, `codigo_dane`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES ('36', '50659', 'Puerto Gaitán', '16', '2026-05-06 02:15:27', '2026-05-06 02:15:27');

-- ==========================================
-- Tabla: barrios (90 registros)
-- ==========================================
DELETE FROM `barrios`;
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('1', 'Belén', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('2', 'Laureles', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('3', 'El Hueco', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('4', 'San Alejo', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('5', 'Junín', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('6', 'Robledo', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('7', 'Arví', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('8', 'Villa del Prado', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('9', 'Santo Domingo', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('10', 'Castilla', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('11', 'La Candelaria', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('12', 'Chapinero', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('13', 'Usaquén', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('14', 'Teusaquillo', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('15', 'Barrios Unidos', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('16', 'Kennedy', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('17', 'Puente Aranda', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('18', 'Suba', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('19', 'Usme', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('20', 'Rafael Uribe Uribe', '6', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('21', 'San Antonio', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('22', 'San Joaquín', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('23', 'Menga', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('24', 'La Ferretería', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('25', 'Cristo Rey', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('26', 'San Fernando', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('27', 'Juanchito', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('28', 'Terrón Colorado', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('29', 'Ribera', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('30', 'Versalles', '13', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('31', 'El Prado', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('32', 'Riomar', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('33', 'Castillogrande', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('34', 'Altos del Rosario', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('35', 'Suroriental', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('36', 'La Magdalena', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('37', 'Tabor', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('38', 'Atlántico', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('39', 'Rebolo', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('40', 'San Roque', '18', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('41', 'El Centro', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('42', 'Crespo', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('43', 'Pasacaballos', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('44', 'Las Flores', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('45', 'Campo Hermoso', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('46', 'San Cristóbal', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('47', 'Santander', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('48', 'Paraíso', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('49', 'Nuevo Paraíso', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('50', 'Las Gaviotas', '23', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('51', 'Centro', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('52', 'Cabecera', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('53', 'Río de Oro', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('54', 'Morrosquillo', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('55', 'Mejoras Públicas', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('56', 'San Martín', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('57', 'Los Andes', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('58', 'Provenza', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('59', 'Sotomayor', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('60', 'Circunvalar', '27', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('61', 'Centro', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('62', 'Obrero', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('63', 'Chapal', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('64', 'Anganoy', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('65', 'Álamo', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('66', 'Mocondino', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('67', 'Santa Rosa', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('68', 'San Fernando', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('69', 'La Laguna', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('70', 'Tescual', '32', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('71', 'Centro', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('72', 'Barzal', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('73', 'Acacías', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('74', 'Libertadores', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('75', 'Los Girasoles', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('76', 'Aranda', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('77', 'Las Colinas', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('78', 'Vanguardia', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('79', 'Centro Occidente', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('80', 'El Edén', '35', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('81', 'Centro', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('82', 'La Esmeralda', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('83', 'Santa Rosa', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('84', 'Bolívar', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('85', 'Palestina', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('86', 'San Francisco', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('87', 'Aranda', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('88', 'Las Acacias', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('89', 'Kennedy', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `barrios` (`id`, `nombre`, `municipio_id`, `created_at`, `updated_at`) VALUES ('90', 'Nueva Esperanza', '29', '2026-05-06 02:15:27', '2026-05-06 02:15:27');

-- ==========================================
-- Tabla: tipos_equipos (15 registros)
-- ==========================================
DELETE FROM `tipos_equipos`;
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('1', 'Computador de Escritorio', 'Desktop PC para oficina', 'HARDWARE', 'fa-desktop', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('2', 'Laptop', 'Computadora portátil', 'HARDWARE', 'fa-laptop', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('3', 'Impresora', 'Impresora de oficina', 'PERIFERICO', 'fa-print', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '4');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('4', 'Multifuncional', 'Impresora multifuncional (copia, escaneo, fax)', 'PERIFERICO', 'fa-print', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '4');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('5', 'Router', 'Router de red inalámbrica', 'RED', 'fa-wifi', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '3');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('6', 'Switch', 'Switch de red para conexión de dispositivos', 'RED', 'fa-network-wired', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '3');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('7', 'Servidor', 'Servidor de red/datos', 'HARDWARE', 'fa-server', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('8', 'UPS', 'Sistema de alimentación ininterrumpida', 'HARDWARE', 'fa-battery-full', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('9', 'Monitor', 'Monitor LCD/LED', 'PERIFERICO', 'fa-tv', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '4');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('10', 'Tablet', 'Dispositivo tablet', 'HARDWARE', 'fa-tablet-alt', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('11', 'Celular', 'Teléfono inteligente', 'HARDWARE', 'fa-mobile-alt', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '1');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('12', 'Cámara IP', 'Cámara de seguridad en red', 'RED', 'fa-video', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '3');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('13', 'Firewall', 'Dispositivo de seguridad de red', 'RED', 'fa-shield-alt', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '3');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('14', 'Software Licencia', 'Software con licencia comercial', 'SOFTWARE', 'fa-cube', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '2');
INSERT INTO `tipos_equipos` (`id`, `nombre`, `descripcion`, `categoria`, `icono`, `created_at`, `updated_at`, `categoria_id`) VALUES ('15', 'Sistema Operativo', 'Sistema operativo para equipo', 'SOFTWARE', 'fa-square', '2026-05-06 02:15:27', '2026-05-06 02:15:27', '2');

-- ==========================================
-- Tabla: categorias (5 registros)
-- ==========================================
DELETE FROM `categorias`;
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `color`, `estado`, `created_at`, `updated_at`) VALUES ('1', 'HARDWARE', 'hardware', 'Componentes físicos: computadoras, servidores, componentes electrónicos', 'fa-microchip', '#3b82f6', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `color`, `estado`, `created_at`, `updated_at`) VALUES ('2', 'SOFTWARE', 'software', 'Licencias, aplicaciones, sistemas operativos y programas', 'fa-code', '#10b981', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `color`, `estado`, `created_at`, `updated_at`) VALUES ('3', 'RED', 'red', 'Equipos de conectividad: routers, switches, cableado, firewalls', 'fa-network-wired', '#f59e0b', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `color`, `estado`, `created_at`, `updated_at`) VALUES ('4', 'PERIFERICO', 'periferico', 'Periféricos: impresoras, escáneres, monitores, teclados', 'fa-print', '#ef4444', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `color`, `estado`, `created_at`, `updated_at`) VALUES ('5', 'BIOMEDICO', 'imagenologia', 'imagenes diagnoticas', 'fa-cubes', '#8b5cf6', '1', '2026-05-06 02:15:27', '2026-05-06 02:15:27');

-- Tabla: estado_servicios (vacía)

-- Tabla: roles (vacía)

-- Tabla: permissions (vacía)

-- Tabla: role_permissions (vacía)

SET FOREIGN_KEY_CHECKS=1;
