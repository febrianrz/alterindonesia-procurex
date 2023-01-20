<?php


namespace App\Services\SubMenu;

use App\Http\Resources\SubMenuResource;
use App\Models\SubMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubMenuServiceEloquent implements SubMenuServiceInterface
{
    /**
     * @var SubMenu
     */
    protected SubMenu $model;

    /**
     * @var array
     */
    protected array $result;

    /**
     * SubMenuServiceEloquent constructor.
     * @param SubMenu $model
     */
    public function __construct(SubMenu $model)
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
        // get data sub-menu with paginate format
        $this->result["data"] = SubMenuResource::collection($this->model->paginate())->response()->getData();

        // return result
        return $this->result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        // create new sub-menu
        $subMenu = $this->model->create([
            "menu_id"       => $request->menu_id,
            "name"          => $request->name,
            "icon"          => $request->icon,
            "created_by"    => 1
        ]);

        // set success result
        $this->result["message"] = __("master_data.created");
        $this->result["data"] = new SubMenuResource($subMenu);

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function detail(string $id): array
    {
        // find sub-menu by id
        $subMenu = $this->model->where("id", "=", $id)->first();

        // check sub-menu existence
        if (!is_null($subMenu)) {
            // set success result
            $this->result["data"] = new SubMenuResource($subMenu);
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
        // find sub-menu by id
        $subMenu = $this->find($id);

        // check menu existence
        if (!is_null($subMenu)) {
            // set data sub-menu
            $subMenu->menu_id = $request->menu_id;
            $subMenu->name = $request->name;
            $subMenu->icon = $request->icon;
            $subMenu->status = $request->status;
            $subMenu->updated_by = 1;
            // update data sub-menu
            $subMenu->save();

            // set success result
            $this->result["message"] = __("master_data.updated");
            $this->result["data"] = new SubMenuResource($subMenu);
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
        // find sub-menu by id
        $subMenu = $this->find($id);

        // check menu existence
        if (!is_null($subMenu)) {
            // delete data sub-menu
            $subMenu->delete();

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
        // find sub-menu by id
        $subMenu = $this->model->onlyTrashed()->where("id", "=", $id)->first();

        // check sub-menu existence
        if (!is_null($subMenu)) {
            // restore data sub-menu
            $subMenu->restore();

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
     * @return SubMenu | null
     */
    private function find(string $id): ?SubMenu
    {
        return $this->model
            ->where("id", "=", $id)
            ->where("status", "=", $this->model::STATUS_ACTIVE)
            ->first();
    }
}
