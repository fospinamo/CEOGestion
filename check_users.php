<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

// Ver usuarios
$users = \App\Models\User::select('id', 'name', 'email', 'tipo_rol', 'created_at')->get();
echo "=== USUARIOS EN LA BD ===\n\n";

if ($users->isEmpty()) {
    echo "❌ NO HAY USUARIOS EN LA BD\n";
} else {
    echo "ID | Email | Nombre | Rol\n";
    echo str_repeat("-", 80) . "\n";
    foreach ($users as $u) {
        echo $u->id . " | " . $u->email . " | " . $u->name . " | " . $u->tipo_rol . "\n";
    }
    echo "\nTotal: " . $users->count() . " usuarios\n";
}

echo "\n=== CREAR USUARIO DE PRUEBA ===\n";
echo "Intentando crear usuario: admin@ceogestion.com\n";

// Intentar crear usuario
$user = \App\Models\User::updateOrCreate(
    ['email' => 'admin@ceogestion.com'],
    [
        'name' => 'Administrador',
        'password' => bcrypt('password123'),
        'tipo_rol' => 'admin',
        'email_verified_at' => now(),
    ]
);

echo "✅ Usuario creado/actualizado:\n";
echo "   Email: " . $user->email . "\n";
echo "   Nombre: " . $user->name . "\n";
echo "   Rol: " . $user->tipo_rol . "\n";
echo "   Contraseña: password123 (encriptada en BD)\n";
