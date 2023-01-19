<?php

namespace App\Http\Controllers;

use App\Contracts\RestApiContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HasResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    use HasResource;

    private RestApiContract $service;
    private string $resource = UserResource::class;

    /**
     * @param RestApiContract $service
     */
    public function __construct(
        RestApiContract $service
    ) {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $query = $this->service->index($request);
            $result = $this->toDatatable($this->resource,$query);
            return $this->responseSuccess(__("Success"),$result);
        } catch (\Exception $e){
            return $this->responseError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $query = $this->service->show($id);
            return $this->responseSuccess(__("Success"), $this->toSingleResource($this->resource, $query));
        } catch (\Exception $e){
            return $this->responseError($e->getMessage());
        }
    }
}
