<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @uses Division::purchasingGroups
 */
class Division extends Model
{
    protected $connection = 'pgsql_master_data';

    protected $fillable = array(
        'name',
        'comp_code',
        'purch_group_ids',
        'emp_no',
        'is_svp',
    );

    protected $casts = [
        'purch_group_ids' => 'json',
        'is_svp' => 'boolean',
    ];

    protected $attributes = [
        'is_svp' => false,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'comp_code', 'comp_code');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_no', 'emp_no');
    }
}
