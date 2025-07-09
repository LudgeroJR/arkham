<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'name',
        'price',
    ];
    protected $casts = [
        'price' => 'double',
    ];

    // Materiais usados para craftar ESTE item
    public function materials()
    {
        return $this->belongsToMany(Item::class, 'item_compositions', 'item_id', 'material_id')
            ->withPivot('amount');
    }

    public function materialCompositions()
    {
        return $this->hasMany(\App\Models\ItemComposition::class, 'item_id');
    }

    // Itens que ESTE item serve de material (usado para)
    public function usedFor()
    {
        return $this->belongsToMany(Item::class, 'item_compositions', 'material_id', 'item_id');
    }

    // Pokémons que dropam este item
    public function droppedBy()
    {
        return $this->belongsToMany(Pokedex::class, 'loot', 'item_id', 'pokedex_id');
    }

    // NPCs que vendem este item
    public function soldByNPCs()
    {
        return $this->belongsToMany(NPC::class, 'npc_sells', 'item_id', 'npc_id');
    }

    // NPCs que compram este item
    public function boughtByNPCs()
    {
        return $this->belongsToMany(NPC::class, 'npc_buys', 'item_id', 'npc_id');
    }

    // Quests que dão este item como recompensa
    public function questRewards()
    {
        return $this->belongsToMany(Quest::class, 'item_quest', 'item_id', 'quest_id');
    }
}
