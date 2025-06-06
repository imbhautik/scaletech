<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPayload extends Model
{
    use HasFactory;
    protected $fillable = [
        'payload',
    ];
    protected $casts = [
        'payload' => 'array',
    ];
}
