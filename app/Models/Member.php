<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'avatar', 'discord', 'role_id', 'whatsapp', 'start_in'];

    public function games()
    {
        return $this->hasMany(\App\Models\Game::class);
    }
}