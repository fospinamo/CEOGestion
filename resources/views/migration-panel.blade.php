<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Migraciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .log-output { 
            background: #1e1e1e; 
            color: #00ff00; 
            font-family: 'Courier New', monospace;
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto py-10">
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🚀 Panel de Migraciones</h1>
        <p class="text-gray-600">Ejecutar migraciones y seeders desde web (sin Terminal)</p>
    </div>

    <!-- Status -->
    <div id="status" class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <p class="text-gray-700">
            <span class="font-bold">Estado:</span> 
            <span id="status-text" class="text-blue-600">Cargando...</span>
        </p>
    </div>

    <!-- Buttons -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        
        <button onclick="runMigrations()" 
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition">
            ✅ EJECUTAR MIGRACIONES
        </button>

        <button onclick="runSeeds()" 
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition">
            🌱 EJECUTAR SEEDERS
        </button>

        <button onclick="verifyDatabase()" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition">
            🔍 VERIFICAR BD
        </button>

        <button onclick="downloadSQL()" 
                class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition">
            📥 DESCARGAR SQL
        </button>
    </div>

    <!-- Output -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-3">📋 Salida</h2>
        <div id="output" class="log-output text-sm">Esperando acción...</div>
    </div>

    <!-- Token Info -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
        <p class="text-sm text-yellow-800">
            <strong>⚠️ Token Actual:</strong> <code>{{ $token ?? 'NO_CONFIGURADO' }}</code>
        </p>
    </div>

</div>

<script>
    const token = "{{ $token }}";

    async function runMigrations() {
        addLog('🚀 Iniciando migraciones...');
        try {
            const response = await fetch(`/api/migrate-db?token=${token}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                addLog('✅ MIGRACIONES COMPLETADAS\n' + data.output);
                setTimeout(() => verifyDatabase(), 1000);
            } else {
                addLog('❌ ERROR: ' + data.message);
            }
        } catch (error) {
            addLog('❌ ERROR: ' + error.message);
        }
    }

    async function runSeeds() {
        addLog('🌱 Iniciando seeders...');
        try {
            const response = await fetch(`/api/seed-db?token=${token}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                addLog('✅ SEEDERS COMPLETADOS\n' + data.output);
                setTimeout(() => verifyDatabase(), 1000);
            } else {
                addLog('❌ ERROR: ' + data.message);
            }
        } catch (error) {
            addLog('❌ ERROR: ' + error.message);
        }
    }

    async function verifyDatabase() {
        addLog('🔍 Verificando base de datos...');
        try {
            const response = await fetch(`/api/verify-db`);
            const data = await response.json();
            
            let output = '📊 ESTADO DE LA BD:\n\n';
            
            output += '✅ MAESTRAS:\n';
            for (const [table, count] of Object.entries(data.maestras)) {
                const status = count === 'TABLE_NOT_FOUND' ? '❌' : '✅';
                output += `  ${status} ${table}: ${count}\n`;
            }
            
            output += '\n🗑️ OPERACIONALES (deben estar vacíos):\n';
            for (const [table, count] of Object.entries(data.operacionales)) {
                const status = count === 0 ? '✅' : count === 'TABLE_NOT_FOUND' ? '⚠️' : '❌';
                output += `  ${status} ${table}: ${count}\n`;
            }
            
            addLog(output);
        } catch (error) {
            addLog('❌ ERROR: ' + error.message);
        }
    }

    function downloadSQL() {
        addLog('📥 Descargando SQL...');
        // Este endpoint se creará después
        const link = document.createElement('a');
        link.href = '/db/backup_maestras.sql';
        link.download = 'backup_maestras.sql';
        link.click();
    }

    function addLog(message) {
        const output = document.getElementById('output');
        output.innerHTML = message + '\n\n--- ' + new Date().toLocaleTimeString() + ' ---';
        output.scrollTop = output.scrollHeight;
    }

    // Verificar al cargar
    window.addEventListener('load', () => {
        document.getElementById('status-text').textContent = '✅ Listo';
        verifyDatabase();
    });
</script>

</body>
</html>
