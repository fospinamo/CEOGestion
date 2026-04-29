<?php
require_once __DIR__ . '/vendor/autoload.php';

$hash = '$2y$12$SCbQqisiqY36qzVfU1Qon.LW1tZSg.d6uaz53pIFFNCIqTNqMuEPK';
$password = 'password123';

$valid = password_verify($password, $hash);

echo "Hash: " . $hash . "\n";
echo "Password: " . $password . "\n";
echo "Valid: " . ($valid ? "✓ YES" : "✗ NO") . "\n";

if (!$valid) {
    echo "\nHASH INVÁLIDO - El usuario no puede entrar con esa contraseña\n";
} else {
    echo "\nHash es válido. El problema está en otro lado.\n";
}
