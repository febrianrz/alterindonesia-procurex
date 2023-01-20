<?php

namespace App\Http\Controllers;

use App\Services\SubMenu\SubMenuServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubMenuController extends Controller
{
    /**
     * @var SubMenuServiceInterface
     */
    private SubMenuServiceInterface $service;

    /**
     * MenuController constructor.
     * @param SubMenuServiceInterface $service
     */
    public function __construct(SubMenuServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // get data menu
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
        // create new menu
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
        // find data menu
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
    public function update(Request $request, string $id)
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
