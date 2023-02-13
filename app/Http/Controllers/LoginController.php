<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Libraries\Auth;
use App\Models\User;
use App\Services\Login\LoginService;
use Illuminate\Http\Request;

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
