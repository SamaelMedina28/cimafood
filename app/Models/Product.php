<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'quantity',
        'status',
        'business_id',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
