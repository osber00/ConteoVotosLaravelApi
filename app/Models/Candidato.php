<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function formatos()
    {
        return $this->belongsToMany(Formato::class)->withPivot('votos')->withTimestamps();
    }
}
