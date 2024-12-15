<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Size extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'menu_id',
        'size',
        'stock'
    ];

    public function cartMenus()
    {
        return $this->hasMany(CartMenu::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
