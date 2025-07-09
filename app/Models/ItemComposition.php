<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComposition extends Model
{
    use HasFactory;
    protected $table = 'item_compositions';
    protected $fillable = [
        'item_id',
        'material_id',
        'amount',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function material()
    {
        return $this->belongsTo(Item::class, 'material_id');
    }
}