<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function candidatos()
    {
        return $this->belongsToMany(Candidato::class)->withPivot('votos')->withTimestamps();
    }
}
