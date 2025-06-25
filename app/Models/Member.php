<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'avatar', 'discord', 'role_id'];

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}