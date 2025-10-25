<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmaDigital extends Model
{
    use HasFactory;

    protected $table = 'tbl_firmas_digitales';

    protected $fillable = [
        'tipo_documento',
        'alumno_id',
        'referencia_id',
        'nombre_archivo',
        'ruta_archivo',
        'firmante_id',
        'tipo_firmante',
        'nombre_firmante',
        'cargo_firmante',
        'hash_documento',
        'metadatos',
        'ip_address',
        'user_agent',
        'fecha_firma',
        'verificado',
        'fecha_verificacion',
    ];

    protected $casts = [
        'metadatos' => 'array',
        'fecha_firma' => 'datetime',
        'fecha_verificacion' => 'datetime',
        'verificado' => 'boolean',
    ];

    /**
     * Relación con el alumno (si aplica)
     */
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    /**
     * Relación con el usuario que firmó
     */
    public function firmante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'firmante_id');
    }

    /**
     * Registra una firma digital en un documento PDF
     */
    public static function registrarFirma(
        string $tipoDocumento,
        string $nombreArchivo,
        string $hashDocumento,
        int $firmanteId,
        string $tipoFirmante,
        string $nombreFirmante,
        ?string $cargoFirmante = null,
        ?int $alumnoId = null,
        ?int $referenciaId = null,
        ?string $rutaArchivo = null,
        ?array $metadatos = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'tipo_documento' => $tipoDocumento,
            'alumno_id' => $alumnoId,
            'referencia_id' => $referenciaId,
            'nombre_archivo' => $nombreArchivo,
            'ruta_archivo' => $rutaArchivo,
            'firmante_id' => $firmanteId,
            'tipo_firmante' => $tipoFirmante,
            'nombre_firmante' => $nombreFirmante,
            'cargo_firmante' => $cargoFirmante,
            'hash_documento' => $hashDocumento,
            'metadatos' => $metadatos,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'fecha_firma' => now(),
            'verificado' => true,
        ]);
    }

    /**
     * Genera un hash SHA-256 del contenido de un PDF
     */
    public static function generarHashPDF(string $contenidoPDF): string
    {
        return hash('sha256', $contenidoPDF);
    }

    /**
     * Verifica la integridad de un documento PDF
     */
    public function verificarIntegridad(string $hashActual): bool
    {
        return hash_equals($this->hash_documento, $hashActual);
    }

    /**
     * Obtiene todas las firmas de un documento específico
     */
    public static function obtenerFirmasDocumento(string $nombreArchivo)
    {
        return self::with('firmante')
            ->where('nombre_archivo', $nombreArchivo)
            ->orderBy('fecha_firma', 'asc')
            ->get();
    }

    /**
     * Obtiene las firmas de un alumno por tipo de documento
     */
    public static function obtenerFirmasAlumno(int $alumnoId, ?string $tipoDocumento = null)
    {
        $query = self::with('firmante')
            ->where('alumno_id', $alumnoId);

        if ($tipoDocumento) {
            $query->where('tipo_documento', $tipoDocumento);
        }

        return $query->orderBy('fecha_firma', 'desc')->get();
    }

    /**
     * Verifica si un documento tiene firma de un tipo específico de firmante
     */
    public static function tieneFirma(string $nombreArchivo, string $tipoFirmante): bool
    {
        return self::where('nombre_archivo', $nombreArchivo)
            ->where('tipo_firmante', $tipoFirmante)
            ->exists();
    }

    /**
     * Genera un certificado de verificación de firma
     */
    public function generarCertificadoVerificacion(): array
    {
        return [
            'numero_certificado' => 'CERT-' . str_pad($this->id, 8, '0', STR_PAD_LEFT),
            'tipo_documento' => ucfirst(str_replace('_', ' ', $this->tipo_documento)),
            'nombre_archivo' => $this->nombre_archivo,
            'tipo_firmante' => ucfirst($this->tipo_firmante),
            'firmante' => $this->nombre_firmante,
            'cargo' => $this->cargo_firmante,
            'fecha_firma' => $this->fecha_firma->format('d/m/Y H:i:s'),
            'hash_documento' => $this->hash_documento,
            'ip_origen' => $this->ip_address,
            'algoritmo' => 'SHA-256',
            'verificado' => $this->verificado,
        ];
    }

    /**
     * Marca una firma como no verificada
     */
    public function invalidar(): void
    {
        $this->update([
            'verificado' => false,
            'fecha_verificacion' => now(),
        ]);
    }

    /**
     * Obtiene estadísticas de firmas por tipo de documento
     */
    public static function estadisticasPorTipo(): array
    {
        return self::selectRaw('tipo_documento, COUNT(*) as total')
            ->groupBy('tipo_documento')
            ->get()
            ->pluck('total', 'tipo_documento')
            ->toArray();
    }

    /**
     * Obtiene estadísticas de firmas por firmante
     */
    public static function estadisticasPorFirmante(): array
    {
        return self::selectRaw('tipo_firmante, COUNT(*) as total')
            ->groupBy('tipo_firmante')
            ->get()
            ->pluck('total', 'tipo_firmante')
            ->toArray();
    }
}
