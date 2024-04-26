<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tb_estrategia_wms_horario_prioridade';

    protected $primaryKey = 'cd_estrategia_wms_horario_prioridade';

    protected $fillable = [
        'cd_estrategia_wms',
        'ds_horario_inicio',
        'ds_horario_final',
        'nr_prioridade'
    ];
}
