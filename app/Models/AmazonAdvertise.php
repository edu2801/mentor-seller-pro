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
}
