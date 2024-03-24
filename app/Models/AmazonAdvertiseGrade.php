<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonAdvertiseGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'amazon_advertises_id',
        'grade',
        'type',
        'message',
    ];

    public function amazonAdvertises()
    {
        return $this->belongsTo(AmazonAdvertise::class, 'amazon_advertises_id', 'id');
    }
}
