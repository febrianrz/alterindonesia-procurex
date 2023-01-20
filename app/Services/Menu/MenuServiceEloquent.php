<?php


namespace App\Services\Menu;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuServiceEloquent implements MenuServiceInterface
{
    /**
     * @var Menu
     */
    protected Menu $model;

    /**
     * @var array
     */
    protected array $result;

    /**
     * MenuServiceEloquent constructor.
     * @param Menu $model
     */
    public function __construct(Menu $model)
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
        // get data menu with paginate format
        $this->result["data"] = MenuResource::collection($this->model->paginate())->response()->getData();

        // return result
        return $this->result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        // create new menu
        $menu = $this->model->create([
            "module_id"     => $request->module_id,
            "name"          => $request->name,
            "icon"          => $request->icon,
            "created_by"    => 1
        ]);

        // set success result
        $this->result["message"] = __("master_data.created");
        $this->result["data"] = new MenuResource($menu);

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function detail(string $id): array
    {
        // find menu by id
        $menu = $this->model->where("id", "=", $id)->first();

        // check menu existence
        if (!is_null($menu)) {
            // set success result
            $this->result["data"] = new MenuResource($menu);
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
        // find menu by id
        $menu = $this->find($id);

        // check menu existence
        if (!is_null($menu)) {
            // set data menu
            $menu->module_id = $request->module_id;
            $menu->name = $request->name;
            $menu->icon = $request->icon;
            $menu->status = $request->status;
            $menu->updated_by = 1;
            // update data menu
            $menu->save();

            // set success result
            $this->result["message"] = __("master_data.updated");
            $this->result["data"] = new MenuResource($menu);
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
        // find menu by id
        $menu = $this->find($id);

        // check menu existence
        if (!is_null($menu)) {
            // delete data module
            $menu->delete();

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
        // find menu by id
        $menu = $this->model->onlyTrashed()->where("id", "=", $id)->first();

        // check menu existence
        if (!is_null($menu)) {
            // restore data menu
            $menu->restore();

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
     * @return Menu | null
     */
    private function find(string $id): ?Menu
    {
        return $this->model
            ->where("id", "=", $id)
            ->where("status", "=", $this->model::STATUS_ACTIVE)
            ->first();
    }
}
