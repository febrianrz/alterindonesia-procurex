<?php
namespace App\Services\Login;

use App\Models\User;

interface LoginInterface {
    public function login($username,$password) : array;
    public function logout(\App\Models\User $user);
    public function updateProfile(\App\Models\User $user);
    public function updatePassword(\App\Models\User $user);
}
