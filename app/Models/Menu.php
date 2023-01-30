<?php

namespace App\Models;

use App\Traits\HasActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    const STATUS_ACTIVE = "ACTIVE";

    use HasFactory, SoftDeletes, HasActionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["module_id", "name", "icon", "status", "created_by", "updated_by"];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
