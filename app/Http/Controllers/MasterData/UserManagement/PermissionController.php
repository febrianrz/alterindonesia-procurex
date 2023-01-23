<?php

namespace App\Http\Controllers\MasterData\UserManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\JsonResponse;

class PermissionController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionRequest $request
     * @return JsonResponse
     */
    public function store(PermissionRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param PermissionRequest $request
     * @return JsonResponse
     */
    public function update(string $id, PermissionRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
