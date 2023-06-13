<?php


namespace App\Services\MasterData\EmployeeManagement;


use App\Http\Resources\EmployeeResource;
use App\Libraries\Auth;
use App\Models\Employee;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Http\JsonResponse;

class EmployeeServiceEloquent extends MasterDataServiceEloquent
{
    private static string $KODE_LEVEL_VP_ANGGARAN = "VP Anggaran";
    /**
     * EmployeeServiceEloquent constructor.
     *
     * @param Employee $model
     * @param string $resource
     */
    public function __construct(Employee $model, $resource = EmployeeResource::class)
    {
        parent::__construct($model, $resource);
    }

    /**
     * @return array
     */
    public function getSuperior(): array
    {
        // find employee by user
        $employeeData = $this->findEmployeeByUser(Auth::user()->username);
        if (!$employeeData["status"]) {
            return $employeeData;
        }
        $employee = $employeeData["data"];
        if(request()->input('level') && request()->input('level') === self::$KODE_LEVEL_VP_ANGGARAN){
            $superiorData = $this->findEmployeeHasRoles($employee, [self::$KODE_LEVEL_VP_ANGGARAN]);
        } else {
            // find first superior
            $superiorData = $this->findSuperiorByEmployeeNumber($employee->sup_emp_no);
            if (!$superiorData["status"] && !$superiorData["data"]->isEmpty()) {
                // find second superior
                $superiorData = $this->findSuperiorByEmployeeNumber($superiorData["data"][0]->sup_emp_no);
            }
        }
        // check final result
        if (!$superiorData["status"]) {
            return $superiorData;
        }

        // set superior data
        $this->result["data"] = $superiorData["data"];

        return $this->result;

    }

    /**
     * @param string $username
     * @return array
     */
    private function findEmployeeByUser(string $username): array
    {
        // set default result
        $result = [
            "status"    => true,
            "code"      => "",
            "message"   => "",
            "data"      => [],
        ];

        // find employee
        $employee = $this->model->where("emp_no", $username)->get();
        if ($employee->isEmpty()) {
            $result["code"] = JsonResponse::HTTP_BAD_REQUEST;
            $result["message"] = "User is not registered as employee";
        }

        // check employee data
        if ($employee->isEmpty() || (is_null($employee[0]->sup_emp_no) || $employee[0]->sup_emp_no == "")) {
            $result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $result["message"] = __("{$this->messageKey}.not_found");
            $result['status'] = false;
        }
        // check status
        if ($result["status"]) {
            // set employee data
            $result["data"] = $employee[0];
        }

        return $result;
    }

    /**
     * @param string $employeeNumber
     * @return array
     */
    private function findSuperiorByEmployeeNumber(string $employeeNumber): array
    {
        // set default result
        $result = [
            "status"    => true,
            "code"      => "",
            "message"   => "",
            "data"      => collect(),
        ];

        // Checking Grade First
        $grade = request()?->input('level');
        $arrGrade = match ($grade) {
            "AVP" => ['3A', '3B'],
            "VP" => ['2A', '2B'],
            "SVP" => ['1A', '1B'],
            default => [],
        };

        if(count($arrGrade) === 0) {
            $result["status"] = false;
            $result["code"] = JsonResponse::HTTP_BAD_REQUEST;
            $result["message"] = __("Invalid Grade");
            return $result;
        }

        $employee = null;
        // find superior
        $superior = $this->model->where("emp_no", $employeeNumber)->get();

        if(!$superior->isEmpty() && in_array($superior[0]->emp_grade,$arrGrade)) {
            $employee = $superior;
        }

        // find layer2 superior
        if(!$employee){
            $superiorLayer2 = $this->model->where('emp_no',$superior[0]->sup_emp_no)->get();
            if(!$superiorLayer2->isEmpty() && in_array($superiorLayer2[0]->emp_grade,$arrGrade)) {
                $employee = $superiorLayer2;
            }
        }

        if($employee){
            $result['data'] = $employee;
        } else {
            $result["status"] = false;
            $result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $result["message"] = __("{$this->messageKey}.not_found");
        }

        return $result;
    }

    /**
     * @param string $employeeNumber
     * @return array
     */
    private function findEmployeeHasRoles(string $employeeNumber, array $roles): array
    {
        // set default result
        $result = [
            "status"    => true,
            "code"      => "",
            "message"   => "",
            "data"      => collect(),
        ];

        $employee = null;
        // find superior
        $employee = Employee::whereHas('user', function ($query) use($roles){
            $query->whereHas('roles', function ($query) use($roles){
                $query->whereIn('name',$roles);
            });
        })->get();
        if($employee){
            $result['data'] = $employee;
        } else {
            $result["status"] = false;
            $result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $result["message"] = __("{$this->messageKey}.not_found");
        }

        return $result;
    }
}
