<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'completed',
        'user_id',
        'advertise_id',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->updated_by = Auth::user()->id;
        });
    }
}
