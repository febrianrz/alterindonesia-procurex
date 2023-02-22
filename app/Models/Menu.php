<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    const STATUS_ACTIVE = "ACTIVE";

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["module_id", "name", "icon", "order_no", "path", "status", "created_by", "updated_by"];

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function submenus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubMenu::class)->orderBy('order_no');
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Service::class,'menu_service_routes','menu_id','service_id')
            ->withPivot('route');
    }
}
