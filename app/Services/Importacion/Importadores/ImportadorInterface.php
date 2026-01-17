<?php

namespace App\Services\Importacion\Importadores;

use Illuminate\Http\UploadedFile;

interface ImportadorInterface
{
    /**
     * Analiza el archivo y retorna preview sin guardar
     */
    public function analizar(UploadedFile $file): array;

    /**
     * Importa los datos confirmados
     */
    public function importar(array $datos, array $opciones = []): array;

    /**
     * Retorna los campos requeridos para la plantilla
     */
    public function getCamposRequeridos(): array;

    /**
     * Retorna los campos opcionales para la plantilla
     */
    public function getCamposOpcionales(): array;

    /**
     * Nombre de la entidad (para mensajes)
     */
    public function getNombreEntidad(): string;
}
