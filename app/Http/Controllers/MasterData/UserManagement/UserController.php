<?php

namespace App\Http\Controllers\MasterData\UserManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update(string $id, UserRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
