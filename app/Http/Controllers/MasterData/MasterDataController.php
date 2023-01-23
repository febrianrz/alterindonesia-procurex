<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Services\MasterData\MasterDataServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * @var MasterDataServiceInterface
     */
    protected MasterDataServiceInterface $service;

    protected $request;

    /**
     * MasterDataController constructor.
     * @param MasterDataServiceInterface $service
     */
    public function __construct(MasterDataServiceInterface $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
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
     * @return JsonResponse
     */
    public function storeData(): JsonResponse
    {
        // create new menu
        $result = $this->service->create($this->request);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

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
     * @param  string  $id
     * @return JsonResponse
     */
    public function updateData(string $id): JsonResponse
    {
        // update data module
        $result = $this->service->update($id, $this->request);

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
