<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'img',
        'description',
        'sku',
        'qr_code',
        'category_id'
    ];

    public function cartMenus()
    {
        return $this->hasMany(CartMenu::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
