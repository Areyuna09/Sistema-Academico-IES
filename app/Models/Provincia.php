<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'tbl_provincias';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'pais_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }
}
