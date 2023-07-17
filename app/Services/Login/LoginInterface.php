<?php
namespace App\Services\Login;

use App\Models\User;
use Illuminate\Http\Request;

interface LoginInterface {
    public function login($username,$password) : array;
    public function logout(\App\Models\User $user);
    public function updateProfile(\App\Models\User $user,Request $request);
    public function updatePassword(\App\Models\User $user,Request $request);
    public function refreshJwt(\App\Models\User $user);
}
