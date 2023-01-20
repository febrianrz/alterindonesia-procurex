<?php

namespace App\Http\Controllers;

use App\Services\Module\ModuleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * @var ModuleServiceInterface
     */
    private ModuleServiceInterface $service;

    /**
     * ModuleController constructor.
     * @param ModuleServiceInterface $service
     */
    public function __construct(ModuleServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // get data module
        $result = $this->service->list();

        // return success response
        return $this->responseSuccess($result["message"], $result["data"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // create new module
        $result = $this->service->create($request);

        // return success response
        return $this->responseSuccess($result["message"], $result["data"], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        // find data module
        $result = $this->service->detail($id);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

        // return success response
        return $this->responseSuccess($result["message"], $result["data"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // update data module
        $result = $this->service->update($id, $request);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

        // return success response
        return $this->responseSuccess($result["message"], $result["data"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // delete data module
        $result = $this->service->delete($id);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

        // return success response
        return $this->responseSuccess($result["message"], $result["data"]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // delete data module
        $result = $this->service->restore($id);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

        // return success response
        return $this->responseSuccess($result["message"], $result["data"]);
    }
}
