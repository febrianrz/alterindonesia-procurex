<?php
namespace Alterindonesia\Procurex\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $guarded = [];
    protected $casts = [
        'payload'   => 'array',
        'response'  => 'array'
    ];
}
