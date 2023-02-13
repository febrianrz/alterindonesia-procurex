<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Login\LoginService;
use Illuminate\Http\Request;
use App\Libraries\Auth;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct(
        LoginService $loginService
    ){
        $this->loginService = $loginService;
    }

    public function doLogin(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $result = $this->loginService->login(
                $request->input('username'),
                $request->input('password'),
            );
            return $this->responseSuccess("Success",$result);
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }

    public function profile(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            return $this->responseSuccess("Success",new LoginResource($user));
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }

    public function updateProfile(UpdateProfileRequest $request) {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $this->loginService->updateProfile($user,$request);
            return $this->responseSuccess(__('Berhasil memperbaharui profile'));
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request) {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $this->loginService->updatePassword($user,$request);
            return $this->responseSuccess(__('Berhasil memperbaharui password'));
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }

    public function refreshToken() {
        try {
            try {
                $user = User::findOrFail(Auth::user()->id);
                $result = $this->loginService->refreshJwt($user);
                return $this->responseSuccess("Success",$result);
            } catch (\Exception $e){
                return $this->responseError($e);
            }
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);
            $this->loginService->logout($user);
            return $this->responseSuccess(__("Logout Success"));
        } catch (\Exception $e){
            return $this->responseError($e);
        }
    }
}
