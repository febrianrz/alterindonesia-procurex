<?php

namespace App\Http\Controllers\MasterData\ModuleManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\ModuleRequest;
use App\Http\Resources\AnonymousCollection;
use App\Models\Module;
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

    public function getMenu(string $module_path) 
    {
        $module = Module::where('path',"/".$module_path)->first();
        /** Jika module path tidak ada, maka ambil main path */
        $module = !$module ? Module::where('path','/')->first() : $module;
        $result = $this->service->getMenu($module);

        // return success response
        return $this->responseSuccess($result["message"], $result["data"], 200, $result["resource"]);
    }
}
