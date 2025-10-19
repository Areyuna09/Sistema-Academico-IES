<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correlativas extends Model
{
    use HasFactory;

    protected $table = 'correlativas';
    protected $fillable = ['materia', 'correlativa'];
    
    public $timestamps = false;
}