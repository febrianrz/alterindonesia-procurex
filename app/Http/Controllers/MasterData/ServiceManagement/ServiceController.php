<?php

namespace App\Http\Controllers\MasterData\ServiceManagement;

use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\JsonResponse;

class ServiceController extends MasterDataController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        return parent::storeData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function update(string $id, ServiceRequest $request): JsonResponse
    {
        return parent::updateData($id);
    }
}
