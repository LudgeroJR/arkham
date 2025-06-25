<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['member_id', 'name', 'nick'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}