<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loot extends Model
{
    use HasFactory;
    protected $table = 'loot';

    public function pokemon()
    {
        return $this->belongsTo(Pokedex::class, 'pokedex_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}