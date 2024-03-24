<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonAdvertiseImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'amazon_advertise_id',
        'url',
        'width',
        'height',
    ];
}
