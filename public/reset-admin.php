<?php
/**
 * Script para resetear usuario de login en producción
 * 
 * Uso en producción (Colombia Hosting):
 * 1. Sube este archivo a /public/reset-admin.php
 * 2. Abre en navegador: https://tu-dominio.com/reset-admin.php
 * 3. Borra el archivo después
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

echo "<h1>🔑 Reset de Usuario Admin</h1>";
echo "<hr>";

try {
    // Crear o actualizar usuario admin
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@ceogestion.com'],
        [
            'name' => 'Administrador',
            'password' => bcrypt('password123'),
            'tipo_rol' => 'admin',
            'email_verified_at' => now(),
        ]
    );

    echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 5px;'>";
    echo "<h2 style='color: green;'>✅ Usuario Configurado Correctamente</h2>";
    echo "<p><strong>Email:</strong> " . $user->email . "</p>";
    echo "<p><strong>Nombre:</strong> " . $user->name . "</p>";
    echo "<p><strong>Rol:</strong> " . $user->tipo_rol . "</p>";
    echo "<p><strong>Contraseña:</strong> password123</p>";
    echo "</div>";

    echo "<hr>";
    echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANTE: Borra este archivo después de usarlo</p>";
    echo "<p>Comando para borrar: <code>rm public/reset-admin.php</code></p>";

} catch (\Exception $e) {
    echo "<div style='background: #ffebee; padding: 20px; border-radius: 5px;'>";
    echo "<h2 style='color: red;'>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
