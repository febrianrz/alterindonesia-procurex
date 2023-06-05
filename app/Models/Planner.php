<?php

namespace App\Models;

use App\Enums\PlannerLevel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planner extends Model
{
    protected $connection = 'pgsql_master_data';

    protected $fillable = [
        'division_id',
        'purch_group_ids',
        'emp_no',
        'level',
    ];

    protected $casts = [
        'division_id' => 'integer',
        'purch_group_ids' => 'json',
        'level' => PlannerLevel::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_no', 'emp_no');
    }
}
