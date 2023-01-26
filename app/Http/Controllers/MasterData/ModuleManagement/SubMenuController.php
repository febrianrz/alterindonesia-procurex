<?php

namespace App\Http\Controllers\MasterData\ModuleManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\SubMenuRequest;
use Illuminate\Http\JsonResponse;

class SubMenuController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param SubMenuRequest $request
     * @return JsonResponse
     */
    public function store(SubMenuRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param SubMenuRequest $request
     * @return JsonResponse
     */
    public function update(string $id, SubMenuRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
