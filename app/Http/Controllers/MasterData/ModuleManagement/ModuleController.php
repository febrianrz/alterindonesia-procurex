<?php

namespace App\Http\Controllers\MasterData\ModuleManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\ModuleRequest;
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
}
