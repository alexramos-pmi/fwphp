<?php

namespace App\Models;

use App\Support\Options;

class Usuario extends Model
{
    protected static string $table = 'users';

    protected static array $fillable = [
        'name',
        'email',
        'password',
        'level',
    ];

    protected static array $appends = ['level_name'];

    public function level_name()
    {
        return Options::Levels()[$this->attributes['level']] ?? null;
    }
}