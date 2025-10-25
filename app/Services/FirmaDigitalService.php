<?php

namespace App\Services;

use App\Models\FirmaDigital;
use App\Models\Configuracion;
use App\Models\ConfiguracionModulo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FirmaDigitalService
{
    /**
     * Verifica si las firmas digitales están habilitadas para un tipo de documento
     */
    public static function firmasHabilitadas(string $tipoDocumento): bool
    {
        $clave = "firmas_digitales_{$tipoDocumento}";
        return ConfiguracionModulo::estaActivo($clave);
    }

    /**
     * Obtiene la ruta de la imagen de firma digital desde configuración
     */
    public static function obtenerRutaFirma(): ?string
    {
        $config = Configuracion::get();

        if ($config->firma_digital_path) {
            return storage_path('app/public/' . $config->firma_digital_path);
        }

        return null;
    }

    /**
     * Obtiene la URL pública de la firma digital
     */
    public static function obtenerUrlFirma(): ?string
    {
        $config = Configuracion::get();

        if ($config->firma_digital_path) {
            return Storage::url($config->firma_digital_path);
        }

        return null;
    }

    /**
     * Obtiene el cargo del firmante desde configuración
     */
    public static function obtenerCargoFirmante(): ?string
    {
        $config = Configuracion::get();
        return $config->cargo_firma;
    }

    /**
     * Registra que se generó un PDF con firma digital
     */
    public static function registrarPDFFirmado(
        string $tipoDocumento,
        string $nombreArchivo,
        string $contenidoPDF,
        User $firmante,
        string $tipoFirmante,
        ?int $alumnoId = null,
        ?int $referenciaId = null,
        ?array $metadatos = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): FirmaDigital {
        // Generar hash del contenido del PDF
        $hashDocumento = FirmaDigital::generarHashPDF($contenidoPDF);

        // Registrar la firma
        return FirmaDigital::registrarFirma(
            tipoDocumento: $tipoDocumento,
            nombreArchivo: $nombreArchivo,
            hashDocumento: $hashDocumento,
            firmanteId: $firmante->id,
            tipoFirmante: $tipoFirmante,
            nombreFirmante: $firmante->nombre,
            cargoFirmante: self::obtenerCargoFirmante(),
            alumnoId: $alumnoId,
            referenciaId: $referenciaId,
            rutaArchivo: null, // Los PDFs se generan on-the-fly, no se guardan
            metadatos: $metadatos,
            ipAddress: $ipAddress,
            userAgent: $userAgent
        );
    }

    /**
     * Verifica la integridad de un PDF firmado
     */
    public static function verificarIntegridadPDF(string $nombreArchivo, string $contenidoPDF): array
    {
        $firmas = FirmaDigital::obtenerFirmasDocumento($nombreArchivo);

        if ($firmas->isEmpty()) {
            return [
                'valido' => false,
                'mensaje' => 'El documento no tiene firmas registradas',
                'firmas' => [],
            ];
        }

        $hashActual = FirmaDigital::generarHashPDF($contenidoPDF);
        $todasValidas = true;
        $firmasDetalle = [];

        foreach ($firmas as $firma) {
            $esValida = $firma->verificarIntegridad($hashActual);

            if (!$esValida) {
                $todasValidas = false;
            }

            $firmasDetalle[] = [
                'tipo_firmante' => $firma->tipo_firmante,
                'nombre_firmante' => $firma->nombre_firmante,
                'cargo' => $firma->cargo_firmante,
                'fecha_firma' => $firma->fecha_firma,
                'valida' => $esValida,
            ];
        }

        return [
            'valido' => $todasValidas,
            'mensaje' => $todasValidas
                ? 'Todas las firmas son válidas y el documento no ha sido modificado'
                : 'El documento ha sido modificado después de ser firmado',
            'hash_actual' => $hashActual,
            'firmas' => $firmasDetalle,
        ];
    }

    /**
     * Obtiene datos para incluir en el pie de página del PDF con firma
     */
    public static function obtenerDatosPieFirma(User $firmante): array
    {
        $rutaFirma = self::obtenerRutaFirma();
        $cargo = self::obtenerCargoFirmante();

        return [
            'tiene_firma' => $rutaFirma !== null,
            'ruta_firma' => $rutaFirma,
            'nombre_firmante' => $firmante->nombre,
            'cargo_firmante' => $cargo,
            'fecha_firma' => now()->format('d/m/Y H:i'),
        ];
    }

    /**
     * Verifica si un usuario puede firmar documentos
     */
    public static function puedefirmar(User $user): bool
    {
        // Solo Directivos, Supervisores y Admin pueden firmar
        return in_array($user->tipo, [1, 5, 6]); // Admin, Directivo, Supervisor
    }

    /**
     * Obtiene el tipo de firmante según el tipo de usuario
     */
    public static function obtenerTipoFirmante(User $user): string
    {
        return match($user->tipo) {
            1 => 'admin',
            5 => 'directivo',
            6 => 'supervisor',
            default => throw new \Exception('El usuario no puede firmar documentos'),
        };
    }

    /**
     * Genera un reporte de firmas realizadas
     */
    public static function generarReporteFirmas(?string $tipoDocumento = null, ?int $firmanteId = null): array
    {
        $query = FirmaDigital::with(['firmante', 'alumno']);

        if ($tipoDocumento) {
            $query->where('tipo_documento', $tipoDocumento);
        }

        if ($firmanteId) {
            $query->where('firmante_id', $firmanteId);
        }

        $firmas = $query->orderBy('fecha_firma', 'desc')->get();

        return [
            'total' => $firmas->count(),
            'por_tipo' => FirmaDigital::estadisticasPorTipo(),
            'por_firmante' => FirmaDigital::estadisticasPorFirmante(),
            'firmas' => $firmas->map(function ($firma) {
                return [
                    'id' => $firma->id,
                    'tipo_documento' => $firma->tipo_documento,
                    'nombre_archivo' => $firma->nombre_archivo,
                    'firmante' => $firma->nombre_firmante,
                    'tipo_firmante' => $firma->tipo_firmante,
                    'fecha_firma' => $firma->fecha_firma,
                    'verificado' => $firma->verificado,
                    'alumno' => $firma->alumno ? $firma->alumno->nombre : null,
                ];
            }),
        ];
    }
}
