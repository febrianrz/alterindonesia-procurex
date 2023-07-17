<?php

namespace App\Http\Controllers\MasterData\ModuleManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\ModuleRequest;
use App\Http\Resources\AnonymousCollection;
use Illuminate\Http\JsonResponse;

class ModuleController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ModuleRequest $request
     * @return JsonResponse
     */
    public function store(ModuleRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param ModuleRequest $request
     * @return JsonResponse
     */
    public function update(string $id, ModuleRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }

    /**
     * @param string $modulePath
     * @return JsonResponse|AnonymousCollection
     */
    public function getMenuByModulePath(string $modulePath): JsonResponse|AnonymousCollection
    {
        // find menu by module path
        $result = $this->service->getMenuByModulePath($modulePath);

        // check result
        if (!$result["status"]) {
            return $this->responseError($result["message"], $result["data"], $result["code"]);
        }

        // return success response
        return $this->responseSuccess($result["message"], $result["data"], JsonResponse::HTTP_OK, $result["resource"]);
    }
}
