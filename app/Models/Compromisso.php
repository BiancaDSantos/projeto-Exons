<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compromisso extends Model
{
    use HasFactory;

    protected $table = 'compromisso';
    protected $primaryKey = 'id_compromisso';
    public $timestamps = false; 

    protected $fillable = [
        'data_inicio',
        'data_fim',
        'intervalo_inicio',
        'intervalo_fim',
        'id_consultor',
    ];

    public function consultor()
    {
        return $this->belongsTo(Consultor::class, 'id_consultor');
    }

}
