-- ========================================
-- INSERT: Usuario Admin
-- ========================================
-- Email: admin@ceogestion.com
-- Password: password123 (hasheado con bcrypt)
-- Role: Admin (rol_id = 1)

INSERT INTO `users` (
    `name`,
    `email`,
    `email_verified_at`,
    `password`,
    `remember_token`,
    `created_at`,
    `updated_at`,
    `role_id`
) VALUES (
    'Administrador',
    'admin@ceogestion.com',
    NOW(),
    '$2y$12$SCbQqisiqY36qzVfU1Qon.LW1tZSg.d6uaz53pIFFNCIqTNqMuEPK',
    NULL,
    NOW(),
    NOW(),
    1
) ON DUPLICATE KEY UPDATE
    `password` = '$2y$12$SCbQqisiqY36qzVfU1Qon.LW1tZSg.d6uaz53pIFFNCIqTNqMuEPK',
    `updated_at` = NOW();

-- ========================================
-- VERIFICAR QUE SE INSERTÓ
-- ========================================
SELECT id, name, email, role_id FROM users WHERE email = 'admin@ceogestion.com';

-- ========================================
-- CREDENCIALES
-- ========================================
-- Email: admin@ceogestion.com
-- Password: password123
-- Role: Admin (acceso total)
-- ========================================
