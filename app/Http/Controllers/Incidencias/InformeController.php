<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class InformeController extends Controller
{
    /**
     * Procesa texto dictado con IA (Deepseek)
     * POST /api/informe/procesar-voz
     */
    public function procesarVozConIA(Request $request)
    {
        $request->validate([
            'texto_capturado' => 'required|string|max:5000',
            'campo' => 'required|in:diagnostico_validacion,observaciones_informe'
        ]);

        $textoDictado = $request->input('texto_capturado');
        $campo = $request->input('campo');

        // ✅ VERIFICAR SI IA ESTÁ HABILITADA
        $iaEnabled = env('ENABLE_IA_PROCESSING', true);
        
        if (!$iaEnabled) {
            return response()->json([
                'success' => true,
                'texto_procesado' => $textoDictado,
                'campo' => $campo,
                'texto_original' => $textoDictado,
                'ia_enabled' => false,
                'message' => 'IA deshabilitada. Texto sin procesar.'
            ]);
        }

        // ✅ VERIFICAR PROVIDER
        $provider = env('IA_PROVIDER', 'deepseek');
        if ($provider !== 'deepseek') {
            return response()->json([
                'success' => false,
                'error' => "Proveedor IA no configurado: $provider",
                'texto_original' => $textoDictado
            ], 503);
        }

        try {
            $textoProcesado = $this->procesarConDeepseek($textoDictado, $campo);

            return response()->json([
                'success' => true,
                'texto_procesado' => $textoProcesado,
                'campo' => $campo,
                'texto_original' => $textoDictado,
                'ia_enabled' => true,
                'require_confirmation' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error procesando voz con IA: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Error al procesar con IA: ' . $e->getMessage(),
                'texto_original' => $textoDictado,
                'ia_enabled' => env('ENABLE_IA_PROCESSING', true)
            ], 500);
        }
    }

    /**
     * Llama a Deepseek API con caché inteligente
     */
    private function procesarConDeepseek($textoDictado, $campo)
    {
        $apiKey = env('DEEPSEEK_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('DEEPSEEK_API_KEY no configurada en .env');
        }

        // ✅ MODO DEMO GRATUITO (PRUEBA SIN GASTAR DINERO)
        if (env('DEEPSEEK_MODE_DEMO', false)) {
            Log::info('🎭 MODO DEMO ACTIVADO: Simulando respuesta sin llamar a API');
            return $this->generarRespuestaDemo($textoDictado, $campo);
        }

        // ✅ VERIFICAR CACHÉ (AHORRA TOKENS)
        if (env('IA_CACHE_ENABLED', true)) {
            $cacheKey = 'ia_response_' . hash('sha256', $textoDictado . $campo);
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                Log::info('Usando respuesta en caché para: ' . substr($textoDictado, 0, 50));
                return $cached;
            }
        }

        // Seleccionar prompt según el campo
        $prompt = $campo === 'diagnostico_validacion' 
            ? $this->getPromptDiagnostico()
            : $this->getPromptObservaciones();

        // Reemplazar placeholder
        $promptFinal = str_replace('{TEXTO_CAPTURADO}', $textoDictado, $prompt);

        // Log para debugging
        Log::info('Llamando Deepseek API para campo: ' . $campo);

        // Llamar API de Deepseek
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(env('IA_API_TIMEOUT', 30))->post('https://api.deepseek.com/chat/completions', [
            'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un editor técnico ESTRICTO. Debes OBLIGATORIAMENTE respetar el formato exacto. Responde SOLO el texto final en 4 párrafos. Sin explicaciones, sin introducciones, sin comentarios. Sigue EXACTAMENTE cada instrucción del prompt.'
                ],
                [
                    'role' => 'user',
                    'content' => $promptFinal
                ]
            ],
            'temperature' => 0.1,
            'max_tokens' => 800,
            'top_p' => 0.7,
        ]);

        if ($response->failed()) {
            Log::error('Deepseek API error: ' . $response->body());
            throw new \Exception('Error en Deepseek API: ' . $response->status());
        }

        $responseData = $response->json();
        
        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new \Exception('Respuesta inesperada de Deepseek API');
        }

        $textoProcesado = $responseData['choices'][0]['message']['content'];

        // ✅ GUARDAR EN CACHÉ (AHORRA TOKENS)
        if (env('IA_CACHE_ENABLED', true)) {
            Cache::put($cacheKey, $textoProcesado, now()->addHours(24));
        }

        return $textoProcesado;
    }

    /**
     * Prompt para campo DIAGNÓSTICO
     */
    private function getPromptDiagnostico()
    {
        return <<<'PROMPT'
INSTRUCCIÓN CRÍTICA: Debes OBLIGATORIAMENTE seguir este formato exacto. No hagas excepciones.

Tu rol: Editor técnico especializado en redacción de informes para ingeniería biomédica y servicios de TI.

ESTRUCTURA OBLIGATORIA - Exactamente 4 párrafos separados por línea en blanco:

PÁRRAFO 1 - Identificación del Equipo (2-3 líneas máximo):
- DEBE incluir: Nombre EXACTO del equipo (modelo, marca si aplica)
- DEBE incluir: Ubicación específica (Sede, Área)
- Ejemplo: "Se realiza diagnóstico del equipo de rayos X modelo Siemens. Equipo ubicado en Sede Central, Área de Radiología."

PÁRRAFO 2 - Actividad Realizada (3-4 líneas máximo):
- DEBE describir: QUÉ acciones se realizaron durante el diagnóstico
- DEBE mencionar: Procedimientos, inspecciones, pruebas ejecutadas
- NO INVENTAR herramientas o acciones no mencionadas
- Ejemplo: "Se procede a realizar inspección visual del equipo. Se ejecutan pruebas de funcionamiento de componentes principales. Se verifica respuesta del sistema ante diferentes configuraciones."

PÁRRAFO 3 - Hallazgo y Estado Final (3-4 líneas máximo):
- DEBE describir: QUÉ se encontró (hallazgo específico)
- DEBE explicar: CÓMO quedó el equipo tras las acciones (estado final)
- DEBE ser específico sobre el resultado
- Ejemplo: "Se detecta que las imágenes generadas presentaban líneas horizontales intermitentes. Tras realizar ajustes de calibración, el equipo ahora genera imágenes claras y sin artefactos. Sistema completamente funcional."

PÁRRAFO 4 - Recomendación y Observación (2-3 líneas máximo):
- DEBE incluir: Recomendación clara basada en hallazgos
- DEBE incluir: Observaciones relevantes o seguimiento necesario
- Ejemplo: "Se recomienda realizar calibración preventiva cada 3 meses. Equipo apto para producción inmediata. Se sugiere registrar este evento para auditoría."

NORMAS DE REDACCIÓN TÉCNICA (OBLIGATORIAS):
✓ Lenguaje: Formal, técnico, objetivo - Tercera persona (Se realiza, Se detecta, Se procede)
✓ SIN invenciones: No agregues datos, herramientas, repuestos no mencionados explícitamente
✓ Correcciones: Tildes, puntuación, concordancia gramatical - TODO
✓ Vocabulario: Palabras precisas (componente, sistema) NO palabras vagas

VALIDACIÓN FINAL (Verifícate):
□ ¿Párrafo 1 identifica equipo y ubicación?
□ ¿Párrafo 2 describe solo actividades realizadas?
□ ¿Párrafo 3 explica hallazgo Y estado final?
□ ¿Párrafo 4 tiene recomendación clara?
□ ¿Exactamente 4 párrafos separados?
□ ¿Sin invenciones de datos?

RESPONDE SOLO EL TEXTO FINAL EN 4 PÁRRAFOS. Sin explicaciones adicionales.

TEXTO A PROCESAR:
{TEXTO_CAPTURADO}
PROMPT;
    }

    /**
     * Prompt para campo OBSERVACIONES
     */
    private function getPromptObservaciones()
    {
        return <<<'PROMPT'
INSTRUCCIÓN CRÍTICA: Debes OBLIGATORIAMENTE seguir este formato exacto. No hagas excepciones.

Tu rol: Editor técnico especializado en redacción de informes para ingeniería biomédica y servicios de TI.

ESTRUCTURA OBLIGATORIA - Exactamente 4 párrafos separados por línea en blanco:

PÁRRAFO 1 - Identificación del Equipo (2-3 líneas máximo):
- DEBE incluir: Nombre EXACTO del equipo (modelo, marca si aplica)
- DEBE incluir: Ubicación específica (Sede, Área)
- Ejemplo: "Se realiza mantenimiento preventivo del equipo de diagnóstico por imagen modelo Philips. Equipo ubicado en Sede Central, Área de Radiología."

PÁRRAFO 2 - Actividad Realizada (3-4 líneas máximo):
- DEBE describir: QUÉ acciones se realizaron durante el mantenimiento
- DEBE mencionar: Procedimientos, reparaciones, limpiezas ejecutadas
- NO INVENTAR herramientas o repuestos no mencionados
- Ejemplo: "Se procede a desmontar componentes internos del equipo. Se realiza limpieza profunda de filtros y sistemas de ventilación. Se reemplazan componentes desgastados identificados. Se ejecutan pruebas de rendimiento completas."

PÁRRAFO 3 - Hallazgo y Estado Final (3-4 líneas máximo):
- DEBE describir: QUÉ se encontró durante el mantenimiento (hallazgos específicos)
- DEBE explicar: CÓMO quedó el equipo después de las acciones (estado operacional)
- DEBE ser específico sobre condición final
- Ejemplo: "Se detecta acumulación significativa de polvo en filtros y válvulas de aire. Tras limpieza y reemplazo de componentes críticos, el equipo se encuentra en condiciones óptimas de funcionamiento. Todos los sistemas responden correctamente a pruebas de validación."

PÁRRAFO 4 - Recomendación y Observación (2-3 líneas máximo):
- DEBE incluir: Recomendación de seguimiento o mantenimiento futuro
- DEBE incluir: Observaciones importantes o restricciones
- Ejemplo: "Se recomienda realizar mantenimiento preventivo cada 3 meses para mantener eficiencia. Equipo completamente operativo y disponible para servicio. Se sugiere registrar este intervalo de mantenimiento en calendario de auditoría."

NORMAS DE REDACCIÓN TÉCNICA (OBLIGATORIAS):
✓ Lenguaje: Formal, técnico, objetivo - Tercera persona (Se realiza, Se detecta, Se procede)
✓ SIN invenciones: No agregues datos, herramientas, repuestos no mencionados explícitamente
✓ Correcciones: Tildes, puntuación, concordancia gramatical - TODO
✓ Vocabulario: Palabras precisas (componente, sistema, circuito) NO palabras vagas

VALIDACIÓN FINAL (Verifícate):
□ ¿Párrafo 1 identifica equipo y ubicación?
□ ¿Párrafo 2 describe solo actividades realizadas?
□ ¿Párrafo 3 explica hallazgo Y estado final?
□ ¿Párrafo 4 tiene recomendación clara?
□ ¿Exactamente 4 párrafos separados?
□ ¿Sin invenciones de datos o herramientas?

RESPONDE SOLO EL TEXTO FINAL EN 4 PÁRRAFOS. Sin explicaciones adicionales.

TEXTO A PROCESAR:
{TEXTO_CAPTURADO}
PROMPT;
    }

    /**
     * 🎭 GENERADOR DE RESPUESTA DEMO (PRUEBA GRATUITA)
     * Simula respuesta de IA sin gastar tokens ni dinero
     */
    private function generarRespuestaDemo($textoDictado, $campo)
    {
        if ($campo === 'diagnostico_validacion') {
            return "✅ DIAGNÓSTICO MEJORADO (Modo Demo):\n\n"
                . "El equipo ha sido inspeccionado completamente. Se verificó el estado físico, las conexiones de red, los puertos USB y se ejecutaron pruebas de funcionalidad. "
                . "Se detectó que " . substr($textoDictado, 0, 50) . " ... está en óptimas condiciones. "
                . "Se realizó limpieza preventiva y se actualizaron los drivers. El equipo está listo para producción.\n\n"
                . "(Este texto fue generado en MODO DEMO - No se gastó dinero de API)";
        } else {
            return "📝 OBSERVACIONES MEJORADAS (Modo Demo):\n\n"
                . "**TAREA REALIZADA**\n"
                . "Se ejecutaron todas las pruebas requeridas. El diagnóstico mostró que " . substr($textoDictado, 0, 40) . " "
                . "se encuentran dentro de los parámetros normales.\n\n"
                . "**OBSERVACIONES ADICIONALES**\n"
                . "Se recomienda revisar periódicamente. No se encontraron anomalías críticas.\n\n"
                . "**CONCLUSIÓN**\n"
                . "Equipo operativo y disponible para servicio.\n\n"
                . "(Este texto fue generado en MODO DEMO - No se gastó dinero de API)";
        }
    }
}
