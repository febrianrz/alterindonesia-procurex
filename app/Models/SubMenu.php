<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubMenu extends Model
{
    const STATUS_ACTIVE = "ACTIVE";

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["menu_id", "name", "icon", "status", "created_by", "updated_by", "order_no", "path"];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Service::class,'submenu_service_routes','submenu_id','service_id')
            ->withPivot('route');
    }
}
