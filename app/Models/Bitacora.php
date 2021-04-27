<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function observaciones()
    {
        return $this->hasMany(Observacion::class);
    }
}
