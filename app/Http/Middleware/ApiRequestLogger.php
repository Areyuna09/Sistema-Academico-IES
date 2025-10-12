<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRequestLogger
{
    /**
     * Log de cada request/respuesta API con contexto básico.
     */
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $userId = optional($request->user())->id ?? null;
        $alumnoId = optional($request->user())->alumno_id ?? null;
        $meta = [
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_id' => $userId,
            'alumno_id' => $alumnoId,
        ];

        // Sanitizar input (ocultar posibles claves sensibles)
        $input = $request->except(['password', 'clave', 'token']);
        Log::debug('API request start', array_merge($meta, ['input' => $input]));

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            Log::error('API request exception', array_merge($meta, [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => collect(explode("\n", $e->getTraceAsString()))->take(10)->toArray(),
            ]));
            throw $e;
        }

        $durationMs = (int) ((microtime(true) - $start) * 1000);
        $status = $response->getStatusCode();

        $logData = array_merge($meta, [
            'status' => $status,
            'duration_ms' => $durationMs,
        ]);

        if ($status >= 400) {
            $bodySnippet = null;
            try {
                $content = method_exists($response, 'getContent') ? $response->getContent() : '';
                $bodySnippet = is_string($content) ? mb_substr($content, 0, 500) : null;
            } catch (\Throwable $e) {
                $bodySnippet = null;
            }
            $logData['response_preview'] = $bodySnippet;
            Log::error('API request end (error)', $logData);
        } else {
            Log::info('API request end', $logData);
        }

        return $response;
    }
}

