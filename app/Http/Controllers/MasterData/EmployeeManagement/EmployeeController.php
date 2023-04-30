<?php

namespace App\Http\Controllers\MasterData\EmployeeManagement;

use Alterindonesia\Procurex\Interfaces\RestServiceInterface;
use Alterindonesia\Procurex\Resources\AnonymousCollection;
use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Requests\EmployeeRequest;
use App\Services\MasterData\EmployeeManagement\EmployeeServiceEloquent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends MasterDataController
{
    /**
     * EmployeeController constructor.
     *
     * @param RestServiceInterface $service
     * @param Request $request
     * @param EmployeeServiceEloquent $employeeServiceEloquent
     */
    public function __construct(
        RestServiceInterface $service,
        Request $request,
        EmployeeServiceEloquent $employeeServiceEloquent
    ) {
        parent::__construct($service, $request);

        $this->service = $employeeServiceEloquent;
    }

    /**
     * @return AnonymousCollection|JsonResponse
     */
    public function superior(): AnonymousCollection|JsonResponse
    {
        // find superior
        $result = $this->service->getSuperior();

        // chek result
        if (!$result["status"]) {
            return $this->responseError($result["message"], [], $result["code"]);
        }

        return $this->responseSuccess($result["message"], $result["data"], $result["code"], $result["resource"]);
    }
}
