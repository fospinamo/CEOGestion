<?php
/**
 * LARAVEL 11 - FRONTEND CONTROLLER PARA CPANEL
 * 
 * Este archivo maneja solicitudes cuando .htaccess falla o mod_rewrite no está disponible.
 * Funciona como fallback para ocultar /public/ en servidores compartidos.
 * 
 * Servidor: /public_html/gestion/CEOGestion/
 * URL: https://gestion.simotec.com.co/CEOGestion/
 */

// Definir path base
define('LARAVEL_PATH', __DIR__);
define('PUBLIC_PATH', LARAVEL_PATH . '/public');

// Si la solicitud ya contiene /public/, servir desde ahí directamente
if (strpos($_SERVER['REQUEST_URI'] ?? '', '/public/') !== false) {
    require PUBLIC_PATH . '/index.php';
    exit;
}

// Si NO está en /public/, necesitamos redirigir internamente
// Pero primero verificar que no sea un archivo o directorio real en la raíz
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path_info = parse_url($request_uri, PHP_URL_PATH);

// Extraer la parte relativa (después de /gestion/CEOGestion/)
if (preg_match('#^/gestion/CEOGestion/(.*)$#', $path_info, $matches)) {
    $relative_path = trim($matches[1] ?? '');
    
    // Si es un archivo o directorio real en la raíz, servirlo
    $full_path = LARAVEL_PATH . '/' . $relative_path;
    if ($relative_path && ($relative_path !== 'index.php')) {
        if (file_exists($full_path) && is_file($full_path)) {
            // Es un archivo real
            readfile($full_path);
            exit;
        }
        if (file_exists($full_path) && is_dir($full_path)) {
            // Es un directorio real
            require PUBLIC_PATH . '/index.php';
            exit;
        }
    }
} else {
    $relative_path = trim($path_info, '/');
}

// IMPORTANTE: Ajustar $_SERVER para que Laravel interprete la ruta correctamente
$_SERVER['SCRIPT_FILENAME'] = PUBLIC_PATH . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/gestion/CEOGestion/public/index.php';
$_SERVER['PHP_SELF'] = '/gestion/CEOGestion/public/index.php';

// Laravel debe recibir la ruta sin /public/
// Por ejemplo: /parametros/sedes no /public/parametros/sedes
if (!preg_match('#^/public/#', $_SERVER['REQUEST_URI'] ?? '')) {
    // Ajustar REQUEST_URI para que Laravel lo procese correctamente
    // Quitamos el prefijo de /gestion/CEOGestion/
    if (preg_match('#^/gestion/CEOGestion/(.*)$#', $_SERVER['REQUEST_URI'], $matches)) {
        $_SERVER['REQUEST_URI'] = '/' . ($matches[1] ?? '');
    }
}

// Incluir el index.php real de Laravel desde /public/
require PUBLIC_PATH . '/index.php';





