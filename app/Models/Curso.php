<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'seccion',
    ];
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function bitacora()
    {
        return $this->hasOne(Bitacora::class);
    }
    public function syllabus()
    {
        return $this->hasOne(Syllabus::class);
    }
}
