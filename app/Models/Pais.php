<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'tbl_paises';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo_iso',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'pais_id');
    }
}
