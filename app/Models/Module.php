<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Module extends Model
{
    const STATUS_ACTIVE = "ACTIVE";

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["name", "icon", "status", "path", "is_show_on_dashboard", "order_no", "created_by", "updated_by"];

    protected $casts = [
        'is_show_on_dashboard' => 'boolean',
    ];

    public function menus() {
        return $this->hasMany(Menu::class,"module_id")->orderBy('order_no','asc');
    }
}
