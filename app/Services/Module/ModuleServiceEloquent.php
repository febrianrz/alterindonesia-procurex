<?php


namespace App\Services\Module;

use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleServiceEloquent implements ModuleServiceInterface
{
    /**
     * @var Module
     */
    protected Module $model;

    /**
     * @var array
     */
    protected array $result;

    /**
     * ModuleServiceEloquent constructor.
     * @param Module $model
     */
    public function __construct(Module $model)
    {
        $this->model = $model;
        $this->result = [
            "status"    => true,
            "code"      => JsonResponse::HTTP_OK,
            "message"   => __("master_data.get"),
            "data"      => []
        ];
    }

    /**
     * @return array
     */
    public function list(): array
    {
        // get data module with paginate format
        $this->result["data"] = ModuleResource::collection($this->model->paginate())->response()->getData();

        // return result
        return $this->result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        // create new module
        $module = $this->model->create([
            "name"          => $request->name,
            "icon"          => $request->icon,
            "created_by"    => 1
        ]);

        // set success result
        $this->result["message"] = __("master_data.created");
        $this->result["data"] = new ModuleResource($module);

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function detail(string $id): array
    {
        // find module by id
        $module = $this->model->where("id", "=", $id)->first();

        // check module existence
        if (!is_null($module)) {
            // set success result
            $this->result["data"] = new ModuleResource($module);
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @param Request $request
     * @return array
     */
    public function update(string $id, Request $request): array
    {
        // find module by id
        $module = $this->find($id);

        // check module existence
        if (!is_null($module)) {
            // set data module
            $module->name = $request->name;
            $module->icon = $request->icon;
            $module->status = $request->status;
            $module->updated_by = 1;
            // update data module
            $module->save();

            // set success result
            $this->result["message"] = __("master_data.updated");
            $this->result["data"] = new ModuleResource($module);
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function delete(string $id): array
    {
        // find module by id
        $module = $this->find($id);

        // check module existence
        if (!is_null($module)) {
            // delete data module
            $module->delete();

            // set success result
            $this->result["message"] = __("master_data.deleted");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function restore(string $id): array
    {
        // find module by id
        $module = $this->model->onlyTrashed()->where("id", "=", $id)->first();

        // check module existence
        if (!is_null($module)) {
            // restore data module
            $module->restore();

            // set success result
            $this->result["message"] = __("master_data.restored");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return Module | null
     */
    private function find(string $id): ?Module
    {
        return $this->model
            ->where("id", "=", $id)
            ->where("status", "=", $this->model::STATUS_ACTIVE)
            ->first();
    }
}
