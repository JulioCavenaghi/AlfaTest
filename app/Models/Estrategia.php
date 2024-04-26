<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estrategia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tb_estrategia_wms';

    protected $primaryKey = 'cd_estrategia_wms';

    protected $fillable = [
        'ds_estrategia_wms',
        'nr_prioridade'
    ];
}
