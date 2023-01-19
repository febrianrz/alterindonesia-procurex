<?php

namespace App\Http\Controllers;

use App\Contracts\RestApiContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    private RestApiContract $service;

    public function __construct(
        RestApiContract $service
    ) {
        $this->service = $service;
    }


    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->service->index($request);
            return $this->responseSuccess(__("Success"),$data);
        } catch (\Exception $e){
            return $this->responseError($e->getMessage());
        }
    }

    public function show(Request $request, $id) {
        try {
            $data = $this->service->index($request, $this->model);
            return $this->responseSuccess(__("Success"),$data);
        } catch (\Exception $e){
            return $this->responseError($e->getMessage());
        }
    }
}
