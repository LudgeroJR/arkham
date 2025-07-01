<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPC extends Model
{
    use HasFactory;
    protected $table = 'npcs';

    public function sells()
    {
        return $this->belongsToMany(Item::class, 'npc_sells', 'npc_id', 'item_id');
    }

    public function buys()
    {
        return $this->belongsToMany(Item::class, 'npc_buys', 'npc_id', 'item_id');
    }
}