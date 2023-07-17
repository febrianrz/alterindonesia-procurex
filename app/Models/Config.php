<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    public $primaryKey = "code";

    protected $casts = [
        'data'  => 'array'
    ];

    public static $SAP_CODE = "SAP";
    public static $SMTP_CODE = "SMTP";
    public static $GATEWAY_CODE = "GATEWAY";
}
