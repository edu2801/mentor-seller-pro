<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonAdvertiseAPlus extends Model
{
    use HasFactory;

    protected $fillable = [
        'amazon_advertises_item_id',
        'content_reference_key',
        'content_type',
    ];
}
