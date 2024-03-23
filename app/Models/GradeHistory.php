<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'amazon_advertise_id',
        'grade'
    ];
}
