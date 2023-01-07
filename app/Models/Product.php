<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'description',
        'images',
        'date_time'
    ];

    // in case each user has a product assigned to their account
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
