<?php
require 'bootstrap/app.php';
$app = app();

echo "📍 RUTAS GENERADAS POR ASSET():\n";
echo "================================\n\n";

echo "asset('empresas/logo.png'):\n";
echo "  → " . asset('empresas/logo.png') . "\n\n";

echo "asset('storage/empresas/logo.png'):\n";
echo "  → " . asset('storage/empresas/logo.png') . "\n\n";

echo "✅ URLs generadas correctamente\n";
