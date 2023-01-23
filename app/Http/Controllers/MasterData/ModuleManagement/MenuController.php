<?php

namespace App\Http\Controllers\MasterData\ModuleManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\MenuRequest;
use Illuminate\Http\JsonResponse;

class MenuController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param MenuRequest $request
     * @return JsonResponse
     */
    public function store(MenuRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param MenuRequest $request
     * @return JsonResponse
     */
    public function update(string $id, MenuRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
