<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'code',
        'name',
        'email',
        'password',
        'company_code',
        'consumer_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_code','code','company_code');
    }

    public function planner(): HasOne
    {
        return $this->hasOne(Planner::class, 'emp_no', 'username');
    }

    public function generalPlanner() {
        return $this->belongsTo(GeneralPlanner::class,'general_planner_id');
    }

    public function specificPlanner() {
        return $this->belongsTo(SpecificPlanner::class,'specific_planner_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'username','emp_no');
    }
}
