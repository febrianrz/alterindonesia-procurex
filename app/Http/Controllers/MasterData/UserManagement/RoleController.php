<?php

namespace App\Http\Controllers\MasterData\UserManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function update(string $id, RoleRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
