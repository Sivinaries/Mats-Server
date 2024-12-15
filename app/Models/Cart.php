<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartMenus()
    {
        return $this->hasMany(CartMenu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
