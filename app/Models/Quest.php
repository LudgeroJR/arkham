<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    use HasFactory;
    protected $table = 'quests';
    protected $fillable = ['name', 'requirements', 'link'];

    public function rewards()
    {
        return $this->belongsToMany(Item::class, 'item_quest', 'quest_id', 'item_id')->withPivot('amount');
    }
}