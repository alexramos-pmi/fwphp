<?php

namespace App\Models;

use App\Support\Options;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioModel extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'cover'
    ];

    protected $appends = [
        'level_name',
    ];

    public function getLevelNameAttribute()
    {
        return Options::Levels()[$this->level] ?? null;
    }
}
