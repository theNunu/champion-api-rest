<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    use HasFactory;

    protected $table = 'champion'; // es llamado de migraciones
    protected  $fillable = [
        'name',
        'description',
        'state' // Incluir 'state' en los campos asignables en masa

    ];

     protected $attributes = [
        'state' => 1, // Valor predeterminado para el campo 'state'
    ];
}
