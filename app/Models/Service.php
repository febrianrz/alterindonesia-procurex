<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'url',
        'created_by',
        'updated_by'
    ];
    protected $casts  = [
        'is_active' => 'boolean'
    ];
}
