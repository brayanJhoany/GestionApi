<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlanDeClases extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'semana', 'proposito',
        'actividad', 'tiempo_precencial', 'actividad_no_precencial',
        'trabajo_autonomo', 'informacion_extra', 'plan_de_clases_id'
    ];
}
