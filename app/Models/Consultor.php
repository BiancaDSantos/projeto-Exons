<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultor extends Model
{
    use HasFactory;

    protected $table = 'consultor';
    protected $primaryKey = 'id_consultor';

    protected $fillable = [
        'nome_completo',
        'valor_por_hora',
    ];

}
