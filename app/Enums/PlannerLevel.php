<?php

namespace App\Enums;

enum PlannerLevel: string
{
    case SVP = 'SVP PP';
    case VP = 'VP PP';
    case AVP = 'AVP PP';
    case STAFF = 'Staff PP';
}
