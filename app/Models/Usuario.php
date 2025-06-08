<?php

namespace App\Models;

use App\Support\Options;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'cover'
    ];

    protected $appends = ['level_name'];

    public function getLevelNameAttribute()
    {
        return Options::Levels()[$this->level] ?? null;
    }
}