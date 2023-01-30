<?php

namespace App\Models;

use App\Traits\HasActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificPlanner extends Model
{
    use HasFactory, HasActionTrait;

    protected $guarded = [];
}
