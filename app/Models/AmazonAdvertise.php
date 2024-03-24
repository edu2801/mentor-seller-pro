<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonAdvertise extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'external_sku',
        'title',
        'description',
        'bullet_points',
        'keywords',
        'status',
        'thumbnail',
        'variation',
        'parent_sku',
        'permalink',
        'price',
        'sold_quantity',
        'visits',
        'account_id'
    ];

    public function images()
    {
        return $this->hasMany(AmazonAdvertiseImage::class);
    }
}
