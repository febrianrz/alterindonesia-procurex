<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->responseSuccess("OK");
        } catch (\Exception $e){
            return $this->responseError($e->getMessage());
        }

    }
}
